# Design System

> A reusable design system for building interfaces that feel **human-made**, not AI-generated.
> Drop this file into any project. Only edit the **Project Tokens** section per project.
> Everything else is a permanent set of principles and rules.

---

## Table of Contents

1. [Philosophy](#1-philosophy)
2. [The Anti-AI Checklist](#2-the-anti-ai-checklist)
3. [Design Tokens](#3-design-tokens)
4. [Typography](#4-typography)
5. [Color Rules](#5-color-rules)
6. [Spacing & Layout](#6-spacing--layout)
7. [Components](#7-components)
8. [Motion](#8-motion)
9. [Iconography](#9-iconography)
10. [Content & Microcopy](#10-content--microcopy)
11. [Accessibility](#11-accessibility)
12. [Pre-Ship Audit](#12-pre-ship-audit)
13. [Further Reading](#13-further-reading)

---

## 1. Philosophy

Most AI-generated UIs fail for the same reason: they default to the **statistical median of web design**. The result is technically fine but instantly forgettable. Recognizable tells include:

- Centered hero + three equal cards.
- Purple/blue gradient on everything.
- Emojis as icons.
- Heavy drop shadows and oversized rounded corners on every element.
- Evenly distributed pastel palettes with no visual hierarchy.
- Inter font everywhere.
- Bouncy, springy animations with no purpose.
- Perfectly symmetric layouts with no breathing room.

**Our goal:** deliberate design decisions a human would make.

**Our method:** define a system, follow it, and break it only on purpose.

---

## 2. The Anti-AI Checklist

Before shipping any screen, verify:

- [ ] **No emojis** in the UI. Use real icons (Lucide, Phosphor, Heroicons).
- [ ] **No three-equal-cards** layout. Break it into a bento, a zigzag, or an asymmetric grid.
- [ ] **No centered hero** unless the page is a single product pitch.
- [ ] **No gradients** unless they serve a purpose (e.g., depth, focus, category).
- [ ] **No oversized radii** (>20px) on buttons or inputs. Save large radii for hero cards or avatars.
- [ ] **No heavy shadows** on flat cards. Use 1px borders + tiny shadows.
- [ ] **No evenly spaced pastel palettes.** One primary, one secondary, one accent.
- [ ] **No default system font stack.** Choose distinctive typefaces.
- [ ] **No bouncy animation** without user intent. Motion should confirm, not entertain.
- [ ] **No filler copy.** Every sentence must earn its place.

---

## 3. Design Tokens

> **Edit this section per project.** Everything else below stays.

### Naming convention

Use a single namespace (e.g., `--color-primary`) so tokens are portable between projects.

### Example token block (Tailwind)

```js
// tailwind.config.js
colors: {
  brand: {
    primary:      '#PRIMARY',    // One loud color. Used for main actions.
    'primary-dark': '#PRIMARY_DARK',
    secondary:    '#SECONDARY',  // Cool contrast.
    accent:       '#ACCENT',     // Stars, highlights, badges.
    support:      '#SUPPORT',    // Optional 4th color for range.
  },
  surface: {
    bg:      '#BACKGROUND',   // Page background. Warm or cool, but never pure white.
    card:    '#CARD',         // Card surface.
    border:  '#BORDER',       // 1px borders.
    muted:   '#MUTED_BG',     // Subtle backgrounds.
  },
  ink: {
    text:    '#TEXT',         // Primary text. Near-black, warm or cool.
    muted:   '#TEXT_MUTED',   // Secondary text.
    inverse: '#TEXT_INVERSE', // On dark backgrounds.
  },
}
```

### Rules

- **One primary color.** Used for the single most important action on any screen.
- **One secondary.** For supporting actions.
- **One accent.** For moments of delight: stars, badges, notifications.
- **Warm backgrounds.** Pure white (`#FFFFFF`) for pages feels clinical. Use a warm off-white or tinted neutral.
- **Text is never pure black.** `#000` on white is harsh. Use near-black with warmth or coolness.

---

## 4. Typography

### Two-font rule

- **Headings font.** Distinctive, expressive, on-brand. Used for `h1`–`h4`.
- **Body font.** Highly readable, neutral. Used for everything else.

Do not use the same font for both. Do not use more than two font families.

### Common pitfalls

| ❌ Avoid | ✅ Prefer |
|---|---|
| Inter for everything | A distinctive heading font |
| Playfair Display on tech sites | Something that matches the domain |
| System fonts as a "clean" choice | A chosen, named font |
| Uppercase for long headings | Sentence case for readability |

### Scale (minimum)

| Role | Mobile | Desktop | Weight |
|---|---|---|---|
| Display (`h1`) | 32–40px | 48–64px | 700–800 |
| Section (`h2`) | 24–28px | 32–40px | 700 |
| Card title (`h3`) | 18–20px | 20–24px | 600–700 |
| Body | 16px | 16–18px | 400 |
| Caption | 12–14px | 12–14px | 500 |

### Letter spacing

- **Display / headings:** slight negative (`-0.01em` to `-0.02em`).
- **Body:** default (`0`).
- **All caps / badges:** positive (`0.05em`).

---

## 5. Color Rules

### Hierarchy

1. **Primary** — the loud color. Reserved for one action per view.
2. **Secondary** — supporting actions, links, tags.
3. **Accent** — highlights only. Stars, badges, celebrated moments.
4. **Neutrals** — 90% of the interface. Backgrounds, borders, text.

**The 60/30/10 rule:**
- 60% neutral (backgrounds, spacing).
- 30% secondary tones (cards, containers).
- 10% primary + accent (actions, highlights).

### Contrast

- Text on background: **≥ 4.5:1** for body, **≥ 3:1** for large text.
- Interactive elements must be distinguishable without color alone.

### Dark mode (if applicable)

- Do not invert. Redesign.
- Reduce saturation on accents. Bright accents burn in dark mode.
- Use pure neutral surfaces, not tinted.

---

## 6. Spacing & Layout

### Spacing scale

Use a fixed scale. Do not invent values.

```
4 · 8 · 12 · 16 · 24 · 32 · 48 · 64 · 96 · 128 (px)
```

### Section rhythm

- **Between major sections:** 80–160px vertical.
- **Between content blocks:** 32–48px.
- **Between related items:** 12–16px.

### Grid

- **Max content width:** 1200–1280px.
- **Use asymmetry.** A 7/5 or 8/4 column split is more interesting than 6/6.
- **Break the grid deliberately.** Not everything needs to align.

### Whitespace

Whitespace is not wasted space. Crowded layouts signal AI defaults.

- When in doubt, add more padding.
- Do not fill every corner with content.

---

## 7. Components

### Buttons

- **Shape:** radius 8–12px. Avoid pills unless the brand calls for it.
- **Fill:** solid color. Gradients are a red flag.
- **Shadow:** none, or a tiny 1px drop.
- **States:** default, hover (darker), active (pressed), disabled (opacity 45%, `cursor: not-allowed`).
- **Primary button:** only ONE per screen. Others are secondary.
- **Text:** sentence case. Not SHOUTING CAPS.

### Cards

- **Border:** 1px subtle border. Not a heavy drop shadow.
- **Radius:** matches button radius or slightly more.
- **Padding:** at least 20px, ideally 24px.
- **Do not float.** Cards sit on the surface, not hover above it.

### Forms

- **Label above input.** Never placeholder-as-label.
- **Input height:** 40–48px.
- **Focus state:** visible ring or border. Never remove without replacement.
- **Error state:** red border + error text below. Never red background.
- **Do not use icons inside inputs** unless the icon is functional (e.g., password visibility).

### Modals

- **Backdrop:** dark, semi-transparent (50–60% black).
- **Panel:** white, radius matching cards, minimal shadow.
- **Motion:** simple fade or 150ms scale-in. No bounce.
- **Escape key closes. Click outside closes.** Always.

### Navigation

- **Sticky top nav.** Max height 64–72px.
- **Visible bottom border.** Do not rely on shadow alone.
- **Logo left, actions right.** Do not center everything.

---

## 8. Motion

### Principles

- **Motion confirms, not entertains.** Every animation should clarify a state change.
- **Fast, not flashy.** 150–300ms for micro-interactions. 300–500ms for larger transitions.
- **Ease-out on entry, ease-in on exit.** Do not use linear unless it's a spinner.
- **Respect `prefers-reduced-motion`.** Always.

### What to animate

- ✅ Button hover, focus, press.
- ✅ Modal open/close.
- ✅ State changes (loading, success, error).
- ✅ Page transitions (subtle).
- ✅ Progress indicators.

### What NOT to animate

- ❌ Elements on scroll, unless it aids comprehension.
- ❌ Bouncing, springing, or elastic motion.
- ❌ Parallax backgrounds.
- ❌ Text that flies in letter-by-letter.
- ❌ Anything longer than 500ms without a reason.

---

## 9. Iconography

### Rules

- **One icon library per project.** Lucide, Phosphor, Heroicons — pick one.
- **Never use emojis as UI icons.** They render differently per OS and signal "AI default".
- **Consistent stroke width.** Usually 1.5–2.5px depending on size.
- **Align to text baseline.** Icons should feel like part of the type, not a sticker.
- **Do not overuse.** Icons support text, not replace it.

### Sizing

- Inline with body text: 16–20px.
- Buttons: 18–22px.
- Feature cards: 24–32px.
- Hero illustrations: 48px+.

---

## 10. Content & Microcopy

### Voice

- **Human.** Write like a person speaks.
- **Direct.** Cut filler. "Click here to get started" → "Start".
- **Warm** where appropriate, but never saccharine.

### Anti-patterns

- ❌ "Elevate your experience." (meaningless)
- ❌ "Seamlessly integrate." (buzzword)
- ❌ "Unlock the power of..." (AI default)
- ❌ Emoji in headings.
- ❌ Exclamation marks in serious contexts.

### Errors and empty states

- Say what happened, then what to do.
- "Something went wrong. Try again." → better: "Couldn't save your changes. Check your connection and try again."
- Empty states should guide, not apologize: "No projects yet. Create your first one to get started."

---

## 11. Accessibility

Non-negotiable:

- **Keyboard navigable.** Every interactive element reachable via Tab.
- **Focus visible.** Never `outline: none` without a replacement.
- **Color is not the only signal.** Use icons, text, or shape.
- **Contrast ≥ 4.5:1** for body text.
- **Alt text** on meaningful images. Empty alt on decorative.
- **Form labels** associated with inputs.
- **Respect user preferences:** `prefers-reduced-motion`, `prefers-color-scheme`.

---

## 12. Pre-Ship Audit

Before showing the project to a stakeholder, run this list:

**Layout**
- [ ] No three-equal-cards sections.
- [ ] Section spacing feels generous, not cramped.
- [ ] At least one asymmetric or bento layout.

**Typography**
- [ ] Two fonts, clear roles.
- [ ] No Inter as the only font.
- [ ] Headings use negative letter spacing.

**Color**
- [ ] One primary color for actions.
- [ ] Warm background, not pure white.
- [ ] Contrast ≥ 4.5:1 for body text.

**Components**
- [ ] Buttons: solid fill, subtle radius, one primary per view.
- [ ] Cards: 1px border, not heavy shadow.
- [ ] Forms: labels above inputs, visible focus states.

**Motion**
- [ ] All animations under 500ms unless justified.
- [ ] `prefers-reduced-motion` respected.

**Iconography**
- [ ] One library, consistent stroke width.
- [ ] Zero emojis in the UI.

**Content**
- [ ] No buzzwords.
- [ ] Empty states guide, not apologize.
- [ ] Error messages are specific.

**Human touch**
- [ ] At least one intentional, human-made detail. A hand-drawn doodle, a texture, a deliberate imperfection.

---

## 13. Further Reading

- **Refactoring UI** by Adam Wathan & Steve Schoger — the modern classic.
- **Every Layout** by Heydon Pickering & Andy Bell — for smart layout patterns.
- **Practical Typography** by Matthew Butterick — for typography fundamentals.
- **Web.dev — Learn Accessibility** — for practical accessibility.
- **Lucide Icons** — [lucide.dev](https://lucide.dev)
- **Coolors** — [coolors.co](https://coolors.co) for palette exploration.
- **Realtime Colors** — [realtimecolors.com](https://realtimecolors.com) to preview palettes on a live UI.

### Videos (mandatory viewing for this project)

- "How to not have AI UI" (multiple creators)
- "Uncodixify your UI"
- "Banned AI UI Patterns"
- "Hallmark — Anti AI Slop UI"

---

## Appendix: Project-Specific Notes

> Fill this section per project. Do not copy between projects.

### AlgoritmaKids — Current Tokens

| Token | Value | Notes |
|---|---|---|
| `brand.primary` | `#FF6B6B` | Coral red. Buttons, links, primary CTAs. |
| `brand.secondary` | `#4ECDC4` | Teal. Secondary actions, decorative blobs. |
| `brand.accent` | `#FCD34D` | Warm yellow. Stars, badges, highlights. |
| `brand.support` | `#9B7EDE` | Purple. Category color for loops. |
| `surface.bg` | `#FFF8F0` | Warm cream. Page background. |
| `surface.card` | `#FFFFFF` | White cards. |
| `surface.border` | `#F0E6D8` | Warm border tone. |
| `ink.text` | `#2D2A26` | Warm near-black. |
| Heading font | **Fredoka 600** | Rounded, playful, distinctive. |
| Body font | **Nunito 400–800** | Readable, warm, pairs with Fredoka. |
| Icon library | **Lucide** | 1.5–2.5px stroke. No emojis. |
| Radius | `20px` (kid) | Slightly larger than default to feel soft. |

### Voice

Playful but clear. Indonesian + English. Age-appropriate for 8–12. Encouraging, never patronizing.

### Signature touch

The animated SVG robot. It is the mascot. It should appear in the hero and celebrate wins.

---

*End of Design System.*