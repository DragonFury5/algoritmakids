
document.addEventListener('alpine:init', () => {
    Alpine.data('robotGame', (config) => ({
        width: config.width,
        height: config.height,
        start: config.start,
        goal: config.goal,
        walls: config.walls.map(w => w[0] + ',' + w[1]),
        maxBlocks: config.max_blocks || 20,

        robot: { x: config.start.x, y: config.start.y, dir: config.start.dir },
        program: [],
        running: false,
        won: false,
        message: '',
        messageType: '',
        starsEarned: 0,

        get cell() { return 64; },

        get robotStyle() {
            return `transform: translate(${this.robot.x * this.cell}px, ${this.robot.y * this.cell}px);`;
        },

        get robotRotation() {
            const a = { right: 0, down: 90, left: 180, up: 270 };
            return `rotate(${a[this.robot.dir]}deg)`;
        },

        isWall(x, y) { return this.walls.includes(x + ',' + y); },

        addBlock(type) {
            if (this.running || this.won) return;
            if (this.program.length >= this.maxBlocks) return;
            this.program.push({ type });
        },

        removeBlock(i) {
            if (this.running) return;
            this.program.splice(i, 1);
        },

        clearProgram() {
            if (this.running) return;
            this.program = [];
            this.reset();
            this.message = '';
            this.messageType = '';
        },

        reset() {
            this.robot = { x: this.start.x, y: this.start.y, dir: this.start.dir };
        },

        sleep(ms) { return new Promise(r => setTimeout(r, ms)); },

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

            for (const block of this.program) {
                const ok = await this.execute(block);
                if (! ok) {
                    this.message = 'Oops! The robot hit a wall.';
                    this.messageType = 'error';
                    this.running = false;
                    return;
                }
                if (this.robot.x === this.goal.x && this.robot.y === this.goal.y) {
                    this.starsEarned = this.program.length <= this.maxBlocks - 3 ? 3 : 2;
                    this.won = true;
                    this.running = false;
                    this.message = '';
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