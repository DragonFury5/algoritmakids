// ==================== SOUND ENGINE ====================
const SoundEngine = (() => {
    let ctx = null;
    function getCtx() {
        if (!ctx) { try { ctx = new (window.AudioContext || window.webkitAudioContext)(); } catch (e) { return null; } }
        if (ctx.state === 'suspended') ctx.resume();
        return ctx;
    }
    function beep(freq, duration, type = 'sine', volume = 0.15, delay = 0) {
        if (!window.soundEnabled) return;
        const c = getCtx();
        if (!c) return;
        const osc = c.createOscillator();
        const gain = c.createGain();
        const t = c.currentTime + delay;
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
        win()   { beep(523, 0.15, 'sine', 0.14, 0); beep(659, 0.15, 'sine', 0.14, 0.14); beep(784, 0.30, 'sine', 0.14, 0.28); },
        fail()  { beep(220, 0.15, 'sine', 0.12, 0); beep(165, 0.30, 'sine', 0.12, 0.14); },
        badge() { beep(1046, 0.10, 'triangle', 0.12, 0); beep(1318, 0.10, 'triangle', 0.12, 0.10); beep(1568, 0.12, 'triangle', 0.12, 0.20); beep(2093, 0.30, 'triangle', 0.14, 0.32); },
    };
})();
window.sound = SoundEngine;

document.addEventListener('alpine:init', () => {
    Alpine.store('loader', {
        visible: false,
        _showTimer: null,
        _hideTimer: null,
        _shownAt: 0,
        delay: 250,
        minDuration: 350,

        start() {
            clearTimeout(this._hideTimer);
            if (this.visible) return;
            clearTimeout(this._showTimer);
            this._showTimer = setTimeout(() => {
                this.visible = true;
                this._shownAt = Date.now();
            }, this.delay);
        },

        stop() {
            clearTimeout(this._showTimer);
            if (!this.visible) return;
            const elapsed = Date.now() - this._shownAt;
            const remaining = Math.max(0, this.minDuration - elapsed);
            clearTimeout(this._hideTimer);
            this._hideTimer = setTimeout(() => { this.visible = false; }, remaining);
        },
    });
});

// Livewire requests (form saves, wire:click actions, etc.)
document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ succeed, fail }) => {
        Alpine.store('loader').start();
        const done = () => Alpine.store('loader').stop();
        succeed(done);
        fail(done);
    });
});

// wire:navigate page transitions
document.addEventListener('livewire:navigating', () => Alpine.store('loader').start());
document.addEventListener('livewire:navigated',  () => Alpine.store('loader').stop());

// Plain <a> clicks that do a full page load
document.addEventListener('click', (e) => {
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    const a = e.target.closest('a');
    if (!a) return;
    if (a.target === '_blank' || a.hasAttribute('download') || a.hasAttribute('wire:navigate')) return;
    const href = a.getAttribute('href') || '';
    if (!href || href.startsWith('#') || /^(mailto:|tel:|javascript:)/.test(href)) return;
    if (a.origin !== location.origin) return;
    Alpine.store('loader').start();
});

// Traditional form posts, such as logging out, also benefit from the same feedback.
document.addEventListener('submit', (e) => {
    const form = e.target;
    if (form.hasAttribute('wire:submit') || form.getAttribute('target') === '_blank') return;
    Alpine.store('loader').start();
});

// Back/forward cache restore — always hide
window.addEventListener('pageshow', () => Alpine.store('loader').stop());

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
        allowConditionals: config.allow_conditionals || false,
        allowWhileLoops: config.allow_while_loops || false,
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
                if (!saved) return;
                const data = JSON.parse(saved);
                
                // FIXED: Removed the ~ typo
                const program = Array.isArray(data) ? data : data.program;
                                          
                if (Array.isArray(program)) {
                    this.program = program.filter(b => b && b.type).map(b => {
                        // Fix old saves that are missing the body array
                        if (['loop', 'while_empty', 'while_wall'].includes(b.type) && !Array.isArray(b.body)) {
                            b.body = [];
                        }
                        return b;
                    });
                }
                if (data && data.focusedContainer) this.focusedContainer = data.focusedContainer;
            } catch (e) {}
        },

        clearSaved() {
            try { localStorage.removeItem('robotPath:' + this.levelId); } catch (e) {}
        },

        get availableWidth() {
            const w = this.windowWidth;
            const isTwoCol = w >= 1024;
            return isTwoCol ? Math.min(540, Math.floor(w * 0.5)) : w - 48;
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

        isPathClear() {
            const d = { right: [1,0], down: [0,1], left: [-1,0], up: [0,-1] }[this.robot.dir];
            const nx = this.robot.x + d[0];
            const ny = this.robot.y + d[1];
            if (nx < 0 || nx >= this.width || ny < 0 || ny >= this.height) return false;
            return !this.isWall(nx, ny);
        },

        getCurrentContainer() {
            if (this.focusedContainer === 'main') return this.program;
            const idx = parseInt(this.focusedContainer);
            const block = this.program[idx];
            if (block && ['loop', 'while_empty', 'while_wall'].includes(block.type)) {
                return block.body;
    
            }
            
            return this.program;
        },

        addBlock(type) {
            if (this.running || this.won) return;

                       // Logic for loop/if blocks (containers)
            if (['loop', 'while_empty', 'while_wall'].includes(type)) {
                if (this.focusedContainer !== 'main') return;
                
                if (type === 'loop' && !this.allowLoops) return;
                if (type !== 'loop' && !this.allowWhileLoops) return;
                
                if (this.program.length >= this.maxBlocks) return;
                
                if (type === 'loop') {
                    this.program.push({ type: 'loop', count: 2, body: [] });
                } else {
                    this.program.push({ type, body: [] });
                }
                
                window.sound.pop();
                return;
            }

            // Logic for simple blocks
            const container = this.getCurrentContainer();
            if (container.length >= this.maxBlocks) return;
            container.push({ type });
            window.sound.pop();
        },

        removeBlock(i) {
            if (this.running) return;
            this.program.splice(i, 1);
            if (this.focusedContainer !== 'main' && !this.program[parseInt(this.focusedContainer)]) {
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
            if (!loop) return;
            loop.count = Math.max(1, Math.min(10, (loop.count || 2) + delta));
            window.sound.click();
        },

        focusMain() { if (!this.running) this.focusedContainer = 'main'; },
        focusLoop(i) { if (!this.running) this.focusedContainer = String(i); },

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

        dismissFail() {
            this.showFailModal = false;
            this.reset();
        },

        sleep(ms) { return new Promise(r => setTimeout(r, ms)); },

        countWrittenBlocks(program) {
            let n = 0;
            for (const b of program) {
                n++;
                               if (['loop', 'while_empty', 'while_wall'].includes(b.type) && Array.isArray(b.body)) {
                    n += this.countWrittenBlocks(b.body);
                }
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

            const won = await this.runBlocks(this.program);
            this.running = false;

                                 if (won) {
                this.won = true;
                const written = this.countWrittenBlocks(this.program);
                this.starsEarned = written <= (this.maxBlocks - 2) ? 3 : 2;
                this.clearSaved();
                
                // Force the save and wait
                try {
                    console.log("Trying to save to database...");
                    await this.$wire.completeLevel(this.starsEarned);
                    console.log("Save successful!");
                } catch (error) {
                    console.error("Database Save Error:", error);
                }
                
                this.showWinModal = true;
                window.sound.win();
            } else if (!this.showFailModal) {
                this.showFailModal = true;
                window.sound.fail();
            }
        },

        async runBlocks(blocks, depth = 0) {
            if (depth > 10) return false; 
            
            for (const block of blocks) {
                if (this.won || (this.robot.x === this.goal.x && this.robot.y === this.goal.y)) return true;
                if (this.showFailModal) return false;

                if (block.type === 'loop') {
                    const times = Math.max(1, Math.min(10, block.count || 1));
                    for (let i = 0; i < times; i++) {
                        const won = await this.runBlocks(block.body, depth + 1);
                        if (won) return true;
                        if (this.showFailModal) return false;
                    }
                } else if (block.type === 'while_empty') {
                    let safety = 0;
                    while (this.isPathClear() && safety < 50) {
                        const won = await this.runBlocks(block.body, depth + 1);
                        if (won) return true;
                        if (this.showFailModal) return false;
                        safety++;
                    }
                } else if (block.type === 'while_wall') {
                    let safety = 0;
                    while (!this.isPathClear() && safety < 50) {
                        const won = await this.runBlocks(block.body, depth + 1);
                        if (won) return true;
                        if (this.showFailModal) return false;
                        safety++;
                    }
                } else {
                    const ok = await this.execute(block);
                    if (!ok) {
                        this.showFailModal = true;
                        return false;
                    }
                }
            }
            
            // 👈 THE FIX: Check one last time after all blocks are executed!
            if (this.robot.x === this.goal.x && this.robot.y === this.goal.y) {
                return true;
            }
            
            return false;
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

    // Pattern Fixer remains unchanged
    Alpine.data('patternFixer', (config) => ({
        // ... keep pattern fixer exactly the same as your current app.js ...
        windowWidth: window.innerWidth,
        width: config.width, height: config.height, start: config.start, goal: config.goal,
        walls: config.walls.map(w => w[0] + ',' + w[1]),
        levelId: config.level_id, options: config.options, originalProgram: config.program,
        robot: { x: config.start.x, y: config.start.y, dir: config.start.dir },
        program: [], editingIndex: null, running: false, won: false,
        showWinModal: false, showFailModal: false, starsEarned: 0, attempts: 0,
        init() { this.resetProgram(); this.$watch('windowWidth', () => {}); },
        resetProgram() { this.program = JSON.parse(JSON.stringify(this.originalProgram)); this.editingIndex = null; this.resetRobot(); },
        get availableWidth() { const w = this.windowWidth; return w >= 1024 ? Math.min(540, Math.floor(w * 0.5)) : w - 48; },
        get cell() { const byWidth = Math.floor(this.availableWidth / this.width); return Math.max(44, Math.min(64, byWidth)); },
        get needsScroll() { return this.width * this.cell > this.availableWidth; },
        get robotStyle() { return `transform: translate(${this.robot.x * this.cell}px, ${this.robot.y * this.cell}px);`; },
        get robotRotation() { const a = { right: 0, down: 90, left: 180, up: 270 }; return `transform: rotate(${a[this.robot.dir] ?? 0}deg);`; },
        isWall(x, y) { return this.walls.includes(x + ',' + y); },
        openEditor(i) { if (this.running || this.won) return; this.editingIndex = i; window.sound.click(); },
        cancelEdit() { this.editingIndex = null; },
        async chooseReplacement(i, type) { if (this.running || this.won) return; this.program[i].type = type; this.editingIndex = null; window.sound.pop(); await this.runProgram(); },
        resetRobot() { this.robot = { x: this.start.x, y: this.start.y, dir: this.start.dir }; },
        dismissFail() { this.showFailModal = false; this.resetRobot(); },
        sleep(ms) { return new Promise(r => setTimeout(r, ms)); },
        async runProgram() {
            if (this.running || this.won) return;
            this.running = true; this.resetRobot(); this.showWinModal = false; this.showFailModal = false; this.attempts++;
            for (const block of this.program) {
                const ok = await this.execute(block);
                if (!ok) { this.running = false; this.showFailModal = true; window.sound.fail(); return; }
                              if (this.robot.x === this.goal.x && this.robot.y === this.goal.y) {
                    this.starsEarned = this.attempts <= 3 ? 3 : 2;
                    this.won = true; 
                    this.running = false;
                    
                    try {
                        console.log("Trying to save to database...");
                        await this.$wire.completeLevel(this.starsEarned);
                        console.log("Save successful!");
                    } catch (error) {
                        console.error("Database Save Error:", error);
                    }
                    
                    this.showWinModal = true; 
                    window.sound.win(); 
                    return;
                }
            }
            this.running = false; this.showFailModal = true; window.sound.fail();
        },
        async execute(block) {
            if (block.type === 'forward') {
                const d = { right: [1,0], down: [0,1], left: [-1,0], up: [0,-1] }[this.robot.dir];
                const nx = this.robot.x + d[0]; const ny = this.robot.y + d[1];
                if (nx < 0 || nx >= this.width || ny < 0 || ny >= this.height) return false;
                if (this.isWall(nx, ny)) return false;
                this.robot.x = nx; this.robot.y = ny; window.sound.move(); await this.sleep(400); return true;
            }
            if (block.type === 'left') { const o = ['right','up','left','down']; this.robot.dir = o[(o.indexOf(this.robot.dir) + 1) % 4]; window.sound.turn(); await this.sleep(250); return true; }
            if (block.type === 'right') { const o = ['right','down','left','up']; this.robot.dir = o[(o.indexOf(this.robot.dir) + 1) % 4]; window.sound.turn(); await this.sleep(250); return true; }
            return true;
        },
    }));
});
