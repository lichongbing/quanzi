<template>
	<view>
		<user-list :list="userList"></user-list>
	</view>
</template>

<script>
	let page;
	import userList from '../../components/user-list/user-list.vue';
	export default {
		components: {
			userList,
		},
		data() {
			return {
				userList: []
			};
		},
		onLoad() {
			page = 1;
			this.getUserList();
		},
		methods: {
			getUserList() {
				this.$H.post('/api/member/follow?page=' + page, {
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					this.userList = this.userList.concat(res[1].data.data)
				})
			}			
		}
	}
</script>

<style lang="scss">
	
</style>