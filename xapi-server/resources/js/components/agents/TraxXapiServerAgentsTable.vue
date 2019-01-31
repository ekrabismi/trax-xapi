<template>

    <trax-ui-ajax-table-with-actions :id="id" :titles="titles" :actions="actions"  
        :props="props" :bus="bus" :endpoint="endpoint" order-column="0" order-dir="desc">
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
                    '',
                    lang.trax_xapi_server.common.id,
                    lang.trax_xapi_server.common.name,
                    lang.trax_xapi_server.common.type,
                    lang.trax_xapi_server.common.members,
                    ''
                ],
                actions: [
                    {name: 'json', icon: 'code', class: 'btn-primary', event: 'json'}
                ],
                endpoint: app_url+'trax/ajax/xapi-server/agents',
                props: [
                    {source: 'updated_at', visible: false },
                    {source: this.renderId, orderable: false },
                    {source: this.renderName, orderable: false },
                    {source: 'data.objectType', orderable: false },
                    {source: this.renderMembers, orderable: false },
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

            renderId(data, type, row, meta) {
                data = row.data;

                // Icon
                var icon = '';
                if (data.mbox) icon = 'email';
                else if (data.mbox_sha1sum) icon = 'email';
                else if (data.openid) icon = 'contact_mail';
                else if (data.account) icon = 'vpn_key';
                icon = '<i class="material-icons trax-text-small mr-1">'+icon+'</i>';

                // Label
                if (data.mbox) return icon+' '+data.mbox.substring(7);
                else if (data.mbox_sha1sum) return icon+' **********';
                else if (data.openid) return icon+' '+data.openid;
                else if (data.account) return icon+' '+'<strong>'+data.account.name+'</strong><br><small>'+data.account.homePage+'</small>';
            },
                
            renderName(data, type, row, meta) {
                data = row.data;
                return (data.name ? data.name : '');
            },

            renderMembers(data, type, row, meta) {
                data = row.data;
                if (data.member) return data.member.length;
                else return '';
            }
        }
    }

</script>
