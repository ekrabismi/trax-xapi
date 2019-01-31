<template>
    <div>

        <!-- Table -->

        <trax-ui-card-plain-header-with-actions :title="lang.trax_xapi_server.common.agents" :actions="card_actions" :bus="bus">
            <trax-xapi-server-agents-table :id="id" :bus="bus"></trax-xapi-server-agents-table>
        </trax-ui-card-plain-header-with-actions>

        <!-- Search modal -->

        <trax-ui-modal-default :id="id+'-search'" :bus="bus" :title="lang.trax_xapi_server.common.search">
            <trax-xapi-server-agents-search :id="id" :bus="bus"></trax-xapi-server-agents-search>
        </trax-ui-modal-default>

        <!-- JSON modal -->

        <trax-xapi-server-json-modal :id="id+'-json'" :bus="bus" :title="lang.trax_xapi_server.common.agent">
        </trax-xapi-server-json-modal>

    </div>
</template>

<script>
    export default {
    
        props: {
            id: {default: 'trax-xapi-server-agents'}
        },
        
        data: function() {
            return {
                lang: lang,
                card_actions: [
                    { icon: 'search', event: this.id+'-search-open' }
                ],
                bus: new Vue()
            }
        }
            
    }
</script>
