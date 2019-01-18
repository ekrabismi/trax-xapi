<template>
    <trax-ui-modal-default :id="id" :bus="bus" :title="title" size="lg">

        <pre class="json" v-html="json"></pre>
    
    </trax-ui-modal-default>
</template>

<script>
    export default {
    
        props: {
            id: null,
            title: {default: lang.trax_xapi_server.common.json},
            bus: null
        },
        
        data: function() {
            return {
                lang: lang,
                json: ''
            }
        },
        
        created: function() {
            this.bus.$on(this.id+'-open', this.setData);
        },
        
        methods: {

            setData(event) {
                this.json = this.jsonHighlight(event.data);
            },
            
            jsonHighlight(json) {
                json = JSON.stringify(json, undefined, 4);
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                    var cls = 'number';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'key';
                        } else {
                            cls = 'string';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'boolean';
                    } else if (/null/.test(match)) {
                        cls = 'null';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                });
            }

        }
    }
</script>
