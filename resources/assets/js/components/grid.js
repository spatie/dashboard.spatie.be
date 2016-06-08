export default {
    template: `
        <div :class="position | grid-from-to">
            <div :class="modifiers | modify-class 'grid__tile'">
                 <slot></slot>
            </div>
        </div>
    `,

    props: ['modifiers', 'position'],

};