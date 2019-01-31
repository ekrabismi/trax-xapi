<template>

    <trax-ui-card :title="lang.trax_xapi_server.home.get_started" class="trax-typo">
        <ol>
            <li v-html="lang.trax_xapi_server.home.get_started_1"></li>
            <li v-html="lang.trax_xapi_server.home.get_started_2"></li>
            <li v-html="lang.trax_xapi_server.home.get_started_3"></li>
            <li v-html="lang.trax_xapi_server.home.get_started_4"></li>
            <li v-html="lang.trax_xapi_server.home.get_started_5"></li>
        </ol>
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
