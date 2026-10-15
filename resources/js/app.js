// ==================== SOUND ENGINE ====================
const SoundEngine = (() => {
    let ctx = null;

    function getCtx() {
        if (! ctx) {
            try {
                ctx = new (window.AudioContext || window.webkitAudioContext)();
            } catch (e) { return null; }
        }
        if (ctx.state === 'suspended') ctx.resume();
        return ctx;
    }

    function beep(freq, duration, type = 'sine', volume = 0.15, delay = 0) {
        if (! window.soundEnabled) return;
        const c = getCtx();
        if (! c) return;

        const osc  = c.createOscillator();
        const gain = c.createGain();
        const t    = c.currentTime + delay;

        osc.type = type;
        osc.frequency.value = freq;

        gain.gain.setValueAtTime(0.0001, t);
        gain.gain.exponentialRampToValueAtTime(volume, t + 0.01);
        gain.gain.exponentialRampToValueAtTime(0.0001, t + duration);

        osc.connect(gain);
        gain.connect(c.destination);

        osc.start(t);
        osc.stop(t + duration + 0.05);
    }

    return {
        click() { beep(900, 0.05, 'triangle', 0.08); },
        pop()   { beep(600, 0.07, 'sine', 0.10); beep(950, 0.06, 'sine', 0.07, 0.03); },
        move()  { beep(320, 0.07, 'sine', 0.06); },
        turn()  { beep(450, 0.08, 'triangle', 0.07); },
        star()  { beep(1200, 0.10, 'triangle', 0.10); },
        win()   {
            beep(523, 0.15, 'sine', 0.14, 0);
            beep(659, 0.15, 'sine', 0.14, 0.14);
            beep(784, 0.30, 'sine', 0.14, 0.28);
        },
        fail()  {
            beep(220, 0.15, 'sine', 0.12, 0);
            beep(165, 0.30, 'sine', 0.12, 0.14);
        },
        badge() {
            beep(1046, 0.10, 'triangle', 0.12, 0);
            beep(1318, 0.10, 'triangle', 0.12, 0.10);
            beep(1568, 0.12, 'triangle', 0.12, 0.20);
            beep(2093, 0.30, 'triangle', 0.14, 0.32);
        },
    };
})();

window.sound = SoundEngine;

// ==================== ALPINE COMPONENTS ====================
document.addEventListener('alpine:init', () => {

    Alpine.data('robotGame', (config) => ({
        windowWidth: window.innerWidth,
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
        showWinModal: false,
        showFailModal: false,
        starsEarned: 0,

        init() {
            this.restoreProgress();
            this.$watch('program', () => this.saveProgress(), { deep: true });
            this.$watch('windowWidth', () => {});
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

        get availableWidth() {
            const w = this.windowWidth;
            const isTwoCol = w >= 1024;
            return isTwoCol
                ? Math.min(540, Math.floor(w * 0.5))
                : w - 48;
        },

        get cell() {
            const byWidth = Math.floor(this.availableWidth / this.width);
            return Math.max(44, Math.min(64, byWidth));
        },

        get needsScroll() {
            return this.width * this.cell > this.availableWidth;
        },

        get robotStyle() {
            return `transform: translate(${this.robot.x * this.cell}px, ${this.robot.y * this.cell}px);`;
        },

        get robotRotation() {
            const a = { right: 0, down: 90, left: 180, up: 270 };
            return `transform: rotate(${a[this.robot.dir] ?? 0}deg);`;
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
                    this.showFailModal = true;
                    return;
                }
                if (this.program.length >= this.maxBlocks) return;
                this.program.push({ type: 'loop', count: 2, body: [] });
                window.sound.pop();
                return;
            }

            const container = this.getCurrentContainer();
            if (container.length >= this.maxBlocks) return;
            container.push({ type });
            window.sound.pop();
        },

        removeBlock(i) {
            if (this.running) return;
            this.program.splice(i, 1);
            if (this.focusedContainer !== 'main' && ! this.program[parseInt(this.focusedContainer)]) {
                this.focusedContainer = 'main';
            }
            window.sound.click();
        },

        removeLoopBlock(loopIdx, i) {
            if (this.running) return;
            const loop = this.program[loopIdx];
            if (loop && loop.body) loop.body.splice(i, 1);
            window.sound.click();
        },

        setLoopCount(loopIdx, delta) {
            if (this.running || this.won) return;
            const loop = this.program[loopIdx];
            if (! loop) return;
            loop.count = Math.max(1, Math.min(10, (loop.count || 2) + delta));
            window.sound.click();
        },

        focusMain() { if (! this.running) this.focusedContainer = 'main'; },
        focusLoop(i) { if (! this.running) this.focusedContainer = String(i); },

        clearProgram() {
            if (this.running) return;
            this.program = [];
            this.focusedContainer = 'main';
            this.reset();
            this.clearSaved();
            window.sound.click();
        },

        reset() {
            this.robot = { x: this.start.x, y: this.start.y, dir: this.start.dir };
        },

        dismissWin() {
            this.showWinModal = false;
        },

        dismissFail() {
            this.showFailModal = false;
            this.reset();
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
                this.showFailModal = true;
                window.sound.fail();
                return;
            }

            this.running = true;
            this.reset();
            this.showWinModal = false;
            this.showFailModal = false;

            const flat = this.flatten(this.program);

            for (const block of flat) {
                const ok = await this.execute(block);
                if (! ok) {
                    this.running = false;
                    this.showFailModal = true;
                    window.sound.fail();
                    return;
                }
                if (this.robot.x === this.goal.x && this.robot.y === this.goal.y) {
                    const written = this.countWrittenBlocks(this.program);
                    const ratio = written / this.maxBlocks;
                    this.starsEarned = ratio <= 0.5 ? 3 : (ratio <= 0.75 ? 2 : 1);
                    this.won = true;
                    this.running = false;
                    this.clearSaved();
                    await this.$wire.completeLevel(this.starsEarned);
                    this.showWinModal = true;
                    window.sound.win();
                    return;
                }
            }

            this.running = false;
            this.showFailModal = true;
            window.sound.fail();
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
                window.sound.move();
                await this.sleep(400);
                return true;
            }
            if (block.type === 'left') {
                const o = ['right','up','left','down'];
                this.robot.dir = o[(o.indexOf(this.robot.dir) + 1) % 4];
                window.sound.turn();
                await this.sleep(250);
                return true;
            }
            if (block.type === 'right') {
                const o = ['right','down','left','up'];
                this.robot.dir = o[(o.indexOf(this.robot.dir) + 1) % 4];
                window.sound.turn();
                await this.sleep(250);
                return true;
            }
            return true;
        },
    }));

    Alpine.data('patternFixer', (config) => ({
        windowWidth: window.innerWidth,
        width: config.width,
        height: config.height,
        start: config.start,
        goal: config.goal,
        walls: config.walls.map(w => w[0] + ',' + w[1]),
        levelId: config.level_id,
        options: config.options,
        originalProgram: config.program,

        robot: { x: config.start.x, y: config.start.y, dir: config.start.dir },
        program: [],
        editingIndex: null,
        running: false,
        won: false,
        showWinModal: false,
        showFailModal: false,
        starsEarned: 0,
        attempts: 0,

        init() {
            this.resetProgram();
            this.$watch('windowWidth', () => {});
        },

        resetProgram() {
            this.program = JSON.parse(JSON.stringify(this.originalProgram));
            this.editingIndex = null;
            this.resetRobot();
        },

        get availableWidth() {
            const w = this.windowWidth;
            const isTwoCol = w >= 1024;
            return isTwoCol
                ? Math.min(540, Math.floor(w * 0.5))
                : w - 48;
        },

        get cell() {
            const byWidth = Math.floor(this.availableWidth / this.width);
            return Math.max(44, Math.min(64, byWidth));
        },

        get needsScroll() {
            return this.width * this.cell > this.availableWidth;
        },

        get robotStyle() {
            return `transform: translate(${this.robot.x * this.cell}px, ${this.robot.y * this.cell}px);`;
        },

        get robotRotation() {
            const a = { right: 0, down: 90, left: 180, up: 270 };
            return `transform: rotate(${a[this.robot.dir] ?? 0}deg);`;
        },

        isWall(x, y) { return this.walls.includes(x + ',' + y); },

        openEditor(i) {
            if (this.running || this.won) return;
            this.editingIndex = i;
            window.sound.click();
        },

        cancelEdit() {
            this.editingIndex = null;
        },

        async chooseReplacement(i, type) {
            if (this.running || this.won) return;
            this.program[i].type = type;
            this.editingIndex = null;
            window.sound.pop();
            await this.runProgram();
        },

        resetRobot() {
            this.robot = { x: this.start.x, y: this.start.y, dir: this.start.dir };
        },

        dismissFail() {
            this.showFailModal = false;
            this.resetRobot();
        },

        sleep(ms) { return new Promise(r => setTimeout(r, ms)); },

        async runProgram() {
            if (this.running || this.won) return;

            this.running = true;
            this.resetRobot();
            this.showWinModal = false;
            this.showFailModal = false;
            this.attempts++;

            for (const block of this.program) {
                const ok = await this.execute(block);
                if (! ok) {
                    this.running = false;
                    this.showFailModal = true;
                    window.sound.fail();
                    return;
                }
                if (this.robot.x === this.goal.x && this.robot.y === this.goal.y) {
                    this.starsEarned = this.attempts <= 1 ? 3 : (this.attempts <= 3 ? 2 : 1);
                    this.won = true;
                    this.running = false;
                    await this.$wire.completeLevel(this.starsEarned);
                    this.showWinModal = true;
                    window.sound.win();
                    return;
                }
            }

            this.running = false;
            this.showFailModal = true;
            window.sound.fail();
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
                window.sound.move();
                await this.sleep(400);
                return true;
            }
            if (block.type === 'left') {
                const o = ['right','up','left','down'];
                this.robot.dir = o[(o.indexOf(this.robot.dir) + 1) % 4];
                window.sound.turn();
                await this.sleep(250);
                return true;
            }
            if (block.type === 'right') {
                const o = ['right','down','left','up'];
                this.robot.dir = o[(o.indexOf(this.robot.dir) + 1) % 4];
                window.sound.turn();
                await this.sleep(250);
                return true;
            }
            return true;
        },
    }));
});