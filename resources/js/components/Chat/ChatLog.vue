<template>
	<div 
	    style="overflow-y: scroll; height:7em;"
	    id="chatList"
	    rows="5"
	    class="border border-secondary"
	    v-html="formatedChat()"
	></div>
</template>
<script>
export default {
    components: {},
    props: {
        chat: {
        	default: () => '[]',
        },
    },
    data() {
        return {
            chatList: '',
        }
    },
    methods: { 
        formatDate(date) {
            return date.getMonth() + '/' + date.getDate() + '/' +
                date.getFullYear() + ' ' + date.getHours() + ':'
                + date.getMinutes() + ':' + date.getSeconds();
        },
        formatedChat() {
            var chatLogFormated = '';
            JSON.parse(this.chat).forEach((item) => {
                var chatFormated = 
                    '<small>' + item.user
                    + ' &lt; <span class="font-italic">' 
                    + this.formatDate(new Date(item.date)) 
                    + '</span> &gt;:<br>' 
                    + item.message + '</small>';
                if (item.isNew) {
                    chatFormated =  '<b>' + chatFormated + '</b>';
                }
                chatLogFormated = chatFormated + '<br>' + chatLogFormated;
            });
            chatLogFormated = chatLogFormated ? chatLogFormated : '<small class="text-muted">This is start of your conversation...</small>';
            
            return chatLogFormated;
        },
    },
    computed: {
    },
    mounted() {
    }
}
</script>