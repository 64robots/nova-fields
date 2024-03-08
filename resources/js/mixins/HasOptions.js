export default {
    methods: {
        /**
         * Just determins if the option could potentially have an option.
         */
        hasOptionHint(option) {
            return typeof option === 'object';
        },

        /**
         * Returns back an option if one is found, otherwise void.
         */
        getOptionHint(option) {
            if (this.hasOptionHint(option)) {
                return option[
                    Object.keys(option).shift()
                ];
            }
        },

        /**
         * Returns back the label of the option.
         */
        getOptionLabel(option) {
            if (this.hasOptionHint(option)) {
                return Object.keys(option).shift();
            }

            return option;
        }
    }
}
