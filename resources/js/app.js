document.addEventListener('alpine:init', () => {
    Alpine.data('robotGame', (config) => ({
        width: config.width,
        height: config.height,
        start: config.start,
        goal: config.goal,
        walls: config.walls.map(w => w[0] + ',' + w[1]),
        maxBlocks: config.max_blocks || 20,
        allowLoops: config.allow_loops || false,
        levelId: config.level_id,

        robot: { x: config.start.x, y: config.start.y, dir: config.start.dir },
        program: [],
        focusedContainer: 'main',
        running: false,
        won: false,
        message: '',
        messageType: '',
        starsEarned: 0,

        init() {
            this.restoreProgress();
            this.$watch('program', () => this.saveProgress(), { deep: true });
        },

        saveProgress() {
            try {
                localStorage.setItem('robotPath:' + this.levelId, JSON.stringify({
                    program: this.program,
                    focusedContainer: this.focusedContainer,
                }));
            } catch (e) {}
        },

        restoreProgress() {
            try {
                const saved = localStorage.getItem('robotPath:' + this.levelId);
                if (! saved) return;
                const data = JSON.parse(saved);
                const program = Array.isArray(data) ? data : data.program;
                if (Array.isArray(program)) {
                    this.program = program.filter(b => b && b.type);
                }
                if (data.focusedContainer) this.focusedContainer = data.focusedContainer;
            } catch (e) {}
        },

        clearSaved() {
            try { localStorage.removeItem('robotPath:' + this.levelId); } catch (e) {}
        },

        get cell() { return 64; },

        get robotStyle() {
            return `transform: translate(${this.robot.x * this.cell}px, ${this.robot.y * this.cell}px);`;
        },

        get robotRotation() {
            const a = { right: 0, down: 90, left: 180, up: 270 };
            return `rotate(${a[this.robot.dir]}deg)`;
        },

        isWall(x, y) { return this.walls.includes(x + ',' + y); },

        getCurrentContainer() {
            if (this.focusedContainer === 'main') return this.program;
            const idx = parseInt(this.focusedContainer);
            if (this.program[idx] && this.program[idx].type === 'loop') {
                return this.program[idx].body;
            }
            return this.program;
        },

        addBlock(type) {
            if (this.running || this.won) return;

            if (type === 'loop') {
                if (! this.allowLoops) return;
                if (this.focusedContainer !== 'main') {
                    this.message = 'Loops can only go in the main program.';
                    this.messageType = 'error';
                    return;
                }
                if (this.program.length >= this.maxBlocks) return;
                this.program.push({ type: 'loop', count: 2, body: [] });
                return;
            }

            const container = this.getCurrentContainer();
            if (container.length >= this.maxBlocks) return;
            container.push({ type });
        },

        removeBlock(i) {
            if (this.running) return;
            this.program.splice(i, 1);
            if (this.focusedContainer !== 'main' && ! this.program[parseInt(this.focusedContainer)]) {
                this.focusedContainer = 'main';
            }
        },

        removeLoopBlock(loopIdx, i) {
            if (this.running) return;
            const loop = this.program[loopIdx];
            if (loop && loop.body) loop.body.splice(i, 1);
        },

        setLoopCount(loopIdx, delta) {
            if (this.running || this.won) return;
            const loop = this.program[loopIdx];
            if (! loop) return;
            loop.count = Math.max(1, Math.min(10, (loop.count || 2) + delta));
        },

        focusMain() { if (! this.running) this.focusedContainer = 'main'; },
        focusLoop(i) { if (! this.running) this.focusedContainer = String(i); },

        clearProgram() {
            if (this.running) return;
            this.program = [];
            this.focusedContainer = 'main';
            this.reset();
            this.message = '';
            this.messageType = '';
            this.clearSaved();
        },

        reset() {
            this.robot = { x: this.start.x, y: this.start.y, dir: this.start.dir };
        },

        sleep(ms) { return new Promise(r => setTimeout(r, ms)); },

        flatten(program) {
            const out = [];
            for (const block of program) {
                if (block.type === 'loop') {
                    const times = Math.max(1, Math.min(10, block.count || 1));
                    for (let i = 0; i < times; i++) out.push(...block.body);
                } else {
                    out.push(block);
                }
            }
            return out;
        },

        countWrittenBlocks(program) {
            let n = 0;
            for (const b of program) {
                n++;
                if (b.type === 'loop' && Array.isArray(b.body)) n += b.body.length;
            }
            return n;
        },

        async run() {
            if (this.running || this.won) return;
            if (this.program.length === 0) {
                this.message = 'Add some blocks first!';
                this.messageType = 'error';
                return;
            }

            this.running = true;
            this.reset();
            this.message = '';
            this.messageType = '';

            const flat = this.flatten(this.program);

            for (const block of flat) {
                const ok = await this.execute(block);
                if (! ok) {
                    this.message = 'Oops! The robot hit a wall.';
                    this.messageType = 'error';
                    this.running = false;
                    return;
                }
                if (this.robot.x === this.goal.x && this.robot.y === this.goal.y) {
                    const written = this.countWrittenBlocks(this.program);
                    const ratio = written / this.maxBlocks;
                    this.starsEarned = ratio <= 0.5 ? 3 : (ratio <= 0.75 ? 2 : 1);
                    this.won = true;
                    this.running = false;
                    this.message = '';
                    this.clearSaved();
                    await this.$wire.completeLevel(this.starsEarned);
                    return;
                }
            }

            this.message = 'The robot didn\'t reach the flag. Try again!';
            this.messageType = 'error';
            this.running = false;
        },

        async execute(block) {
            if (block.type === 'forward') {
                const d = { right: [1,0], down: [0,1], left: [-1,0], up: [0,-1] }[this.robot.dir];
                const nx = this.robot.x + d[0];
                const ny = this.robot.y + d[1];
                if (nx < 0 || nx >= this.width || ny < 0 || ny >= this.height) return false;
                if (this.isWall(nx, ny)) return false;
                this.robot.x = nx;
                this.robot.y = ny;
                await this.sleep(400);
                return true;
            }
            if (block.type === 'left') {
                const o = ['right','up','left','down'];
                this.robot.dir = o[(o.indexOf(this.robot.dir) + 1) % 4];
                await this.sleep(250);
                return true;
            }
            if (block.type === 'right') {
                const o = ['right','down','left','up'];
                this.robot.dir = o[(o.indexOf(this.robot.dir) + 1) % 4];
                await this.sleep(250);
                return true;
            }
            return true;
        },
    }));
});