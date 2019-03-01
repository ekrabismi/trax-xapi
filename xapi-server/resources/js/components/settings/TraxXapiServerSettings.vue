<template>
    <trax-ui-card class="pt-3">

        <!-- Clear all data -->

        <div v-if="deletePermission">
            <h4 class="font-weight-bold mb-2"> {{ lang.trax_xapi_server.common.clear_data_title }} </h4>
            <p class="mb-2"> {{ lang.trax_xapi_server.common.clear_data_desc }} </p>
            <p class="mb-4">

                <!-- Allowed -->
                <trax-ui-ajax-button :label="lang.trax_xapi_server.common.clear_data" :endpoint="endpoint_clear_all" 
                    :confirm-title="lang.trax_xapi_server.common.clear_data_title"
                    v-if="debug == true">
                </trax-ui-ajax-button>

                <!-- Not Allowed -->
                <span class="text-danger" v-else>{{ lang.trax_xapi_server.common.debug_setting }}</span>
            </p>
        </div>

    </trax-ui-card>
</template>

<script>
    export default {
    
        props: {
            debug: null
        },

        data: function() {
            return {
                lang: lang,
                user: user,
                endpoint_clear_all: app_url+'trax/ajax/xapi-server/management/clear-all',
            }
        },

        computed: {

            deletePermission() {
                return this.user.admin || this.user.permissions['xapi_server_delete_xapi_data'];
            }
        }
    }
</script>
