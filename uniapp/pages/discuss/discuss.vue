<template>
	<view>
		<view class="discussInfo">
			<view class="user">
				<u-avatar class="avatar" :src="discussInfo.userInfo.avatar"></u-avatar>
				<view class="user-c">
					<text>{{discussInfo.userInfo.username}}</text>
					<text>发起</text>
				</view>			
			</view>
			<view class="count">
				<text>{{discussInfo.post_count}} 篇内容</text>
				<text>{{discussInfo.read_count}} 次浏览</text>
			</view>
			<view class="discuss-desc">{{discussInfo.introduce}}</view>
		</view>
		<post-list :list="postList" :loadStatus="loadStatus"></post-list>
		<!-- 发布按钮 -->
		<navigator :url="'/pages/plus-post/plus-post?discussId=' + disId" hover-class="none">
			<view class="plus-box">
				<u-icon name="plus" color="#fff" size="50"></u-icon>
			</view>
		</navigator>
	</view>
</template>

<script>
	let page;
	import postList from '../../components/post-list/post-list.vue';
	export default {
		components: {
			postList
		},
		data() {
			return {
				disId: 0,
				loadStatus: "loading",
				postList: [],
				discussInfo: {
					userInfo:{}
				}
			};
		},
		onLoad(options) {
			this.disId = options.id;
			page = 1;
			this.getDiscussInfo();
			this.getPostList();
		},
		methods: {
			getDiscussInfo() {
				this.$H.post('/api/discuss/detail', {
					id: this.disId
				}).then(res => {
					this.discussInfo = res[1].data;
				})
			},
			getPostList() {
				this.loadStatus = "loading";
				this.$H.post('/api/post/discussPost?page=' + page, {
					id: this.disId
				}).then(res => {
					this.postList = this.postList.concat(res[1].data.data);

					if (res[1].data.current_page === res[1].data.last_page || res[1].data.last_page === 0) {
						this.loadStatus = "nomore";
					} else {
						this.loadStatus = "loadmore"
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	@import 'discuss.css';
</style>
