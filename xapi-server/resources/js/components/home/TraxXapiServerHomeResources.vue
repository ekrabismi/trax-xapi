<template>

    <trax-ui-card :title="lang.trax_xapi_server.home.resources" class="trax-typo">
        <ul>
            <li v-html="lang.trax_xapi_server.home.resources_1"></li>
            <li v-html="lang.trax_xapi_server.home.resources_2"></li>
            <li v-html="lang.trax_xapi_server.home.resources_3"></li>
            <li v-html="lang.trax_xapi_server.home.resources_4"></li>
            <li v-html="lang.trax_xapi_server.home.resources_5"></li>
            <li v-html="lang.trax_xapi_server.home.resources_6"></li>
        </ul>
    </trax-ui-card>

</template>

<script>
    export default {
    
        data: function() {
            return {
                lang: lang
            }
        }
    }
</script>
