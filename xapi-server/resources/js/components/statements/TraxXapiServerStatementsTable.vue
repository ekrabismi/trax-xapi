<template>

    <trax-ui-ajax-table-with-actions :id="id" :titles="titles" :actions="actions"  
        :props="props" :bus="bus" :endpoint="endpoint" order-column="0" order-dir="desc" searching="0">
    </trax-ui-ajax-table-with-actions>

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
                titles: [
                    lang.trax_xapi_server.common.stored,
                    lang.trax_xapi_server.common.actor,
                    lang.trax_xapi_server.common.verb,
                    lang.trax_xapi_server.common.object,
                    '',
                    ''
                ],
                actions: [
                    {name: 'json', icon: 'code', class: 'btn-primary', event: 'json'}
                ],
                endpoint: app_url+'trax/ajax/xapi-server/statements',
                props: [
                    {source: this.renderStored, orderable: false },
                    {source: this.renderActor, orderable: false },
                    {source: this.renderVerb, orderable: false },
                    {source: this.renderObject, orderable: false },
                    {source: this.renderResult, orderable: false }
                ]
            }
        },

        created: function() {
            this.bus.$on(this.id+'-json', this.openJson);
        },
        
        methods: {
        
            // Actions

            openJson(data) {
                this.bus.$emit(this.id+'-json-open', {data: data.data});
            },

            // Columns rendering

            renderStored(data, type, row, meta) {
                data = row.data.stored;
                return this.compactTimestamp(data);
            },
                
            renderActor(data, type, row, meta) {
                data = row.data.actor;
                var icon = 'person';
                if (data.objectType == 'Group') icon = 'group';
                return '<i class="material-icons trax-text-small mr-1">'+icon+'</i> '+this.agentLabel(data);
            },
                
            renderVerb(data, type, row, meta) {
                data = row.data.verb;
                if (data.display) return this.langString(data.display);
                return this.twoLinesURI(data.id);
            },
                
            renderObject(data, type, row, meta) {
                data = row.data.object;
                var icon = 'play_circle_filled';
                if (data.objectType == 'Agent') icon = 'person';
                else if (data.objectType == 'Group') icon = 'group';
                else if (data.objectType == 'SubStatement' || data.objectType == 'StatementRef') icon = 'call_missed_outgoing';
                return '<i class="material-icons trax-text-small mr-1">'+icon+'</i> '+this.objectLabel(data);
            },
                
            renderResult(data, type, row, meta) {
                data = row.data;
                if (!data.result) return '';
                return '<span class="badge badge-info">'+this.lang.trax_xapi_server.common.result+'</span>';
            },

            // Utilities

            agentLabel(agent) {
                if (agent.name) return agent.name;
                else if (agent.mbox) return agent.mbox.substring(7);
                else if (agent.mbox_sha1sum) return '**********';
                else if (agent.openid) return agent.openid;
                else if (agent.account) return '<strong>'+agent.account.name+'</strong><br>'+agent.account.homePage;
                else return '';
            },
                
            objectLabel(object) {
                if (object.objectType == 'Agent' || object.objectType == 'Group') return this.agentLabel(object);
                else if (object.objectType == 'SubStatement') return this.lang.trax_xapi_server.common.sub_statement;
                else if (object.objectType == 'StatementRef') return this.lang.trax_xapi_server.common.statement_ref;
                else if (object.definition && object.definition.name) return this.langString(object.definition.name);
                else return this.compactURI(object.id);
            },
                
            langString(data) {
                var keys = Object.keys(data);
                if (keys.length == 0) return '';
                for (var i=0; i<keys.length; i++) {
                    if (keys[i].indexOf(window.locale) !== -1) return data[keys[i]];
                }
                return data[keys[0]];
            },

            twoLinesURI(uri) {
                var parts = uri.split('/');
                return '<strong>'+parts[parts.length-1]+'</strong><br><small>'+parts[2]+'</small>';
            },

            compactURI(uri) {
                var max = 35;
                if (uri.length <= max) return uri;
                var parts = uri.split('/');
                uri = parts[0]+'//'+parts[2]+'/.../'+parts[parts.length-1];
                if (uri.length <= max) return uri;
                return uri.substring(0, max-3)+'...';
            },

            compactTimestamp(timestamp) {
                var timestamp = moment(timestamp);
                timestamp = timestamp.format('DD/MM/YYYY H:mm:ss').split(' ');
                return '<small>'+timestamp[0]+'<br>'+timestamp[1]+'</small>';
            }

        }
    }

</script>
