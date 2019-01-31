<template>
    <form autocomplete="off" class="trax-form-with-icons" v-on:submit.prevent="search">
    
        <!-- ID -->

        <trax-ui-input type="text" icon="http" :placeholder="lang.trax_xapi_server.common.id"
            v-model="form.id">
        </trax-ui-input>

        <!-- Type -->

        <trax-ui-input type="text" icon="http" :placeholder="lang.trax_xapi_server.common.type"
            v-model="form.type">
        </trax-ui-input>

        <!-- Name -->

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_xapi_server.common.name"
            v-model="form.name">
        </trax-ui-input>

        <!-- Description -->

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_xapi_server.common.description"
            v-model="form.description">
        </trax-ui-input>


        <!-- Submit buttons -->

        <div class="trax-form-actions text-right">
            <button type="button" class="btn btn-default btn-link" @click="reset"> {{ lang.trax_xapi_server.common.reset }} </button>
            <button type="submit" class="btn btn-primary btn-round"> {{ lang.trax_xapi_server.common.search }} </button>
        </div>

    </form>
</template>

<script>
    export default {
    
        props: {
            id: null,
            bus: null
        },
        
        data: function() {
            return {
                lang: lang,
                form: {},
            }
        },
        
        methods: {

            search() {
                this.bus.$emit(this.id+'-refresh', this.form);
                this.bus.$emit(this.id+'-search-close');
            },

            reset() {
                this.form = {};
                this.search();
            }
        }
            
    }
</script>
