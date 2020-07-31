<template>
	<view>
		<view class="head">
			<image class="head-bg" src="../../static/images/default/ucenter_bg.png"></image>
			<view class="head-c">
				<u-avatar :src="userInfo.avatar"></u-avatar>
				<view class="username">{{userInfo.username}}
					<block v-if="userInfo.gender == '男'">
						<image src="../../static/images/icon/icon_male.png"></image>
					</block>
					<block v-if="userInfo.gender == '女'">
						<image src="../../static/images/icon/icon_female.png"></image>
					</block>
				</view>
				<text class="city">{{userInfo.province}} {{userInfo.city}}</text>
				<text class="intr">{{userInfo.intro}}</text>
				<view class="num">
					<text>{{userInfo.fans}} 关注</text>
					<text>{{userInfo.follow}} 粉丝</text>
				</view>
			</view>
		</view>
		<u-tabs name="tab_name" :list="tabs" active-color="#dd524d" :is-scroll="false" c :current="current" @change="tabChange"></u-tabs>
		<view v-if="current === 0">
			<post-list :list="postList" :loadStatus="loadStatus"></post-list>
		</view>
		<view v-if="current === 1">
			<topic-list :list="topicList"></topic-list>
		</view>
	</view>
</template>

<script>
	import postList from '../../components/post-list/post-list.vue';
	import topicList from '../../components/topic-list/topic-list.vue';
	let page;
	export default {
		components: {
			postList,
			topicList
		},
		data() {
			return {
				tabs: [{
						tab_name: '帖子'
					},
					{
						tab_name: '圈子'
					}
				],
				current: 0,
				uid: 0,
				postList: [],
				topicList: [],
				userInfo: {},
				loadStatus: "loading"
			};
		},
		onLoad(options) {
			page = 1;
			this.uid = options.uid;
			this.getUserInfo();
			this.getPostList();
		},
		onReachBottom() {
			page++;
			this.getPostList();
		},
		methods: {
			tabChange(index) {
				this.current = index;
				if (index === 1) {
					this.getTopicList();
				}
			},
			getPostList() {
				this.loadStatus = "loading";
				this.$H.post('/api/post/userPost?page=' + page, {
					uid: this.uid
				}).then(res => {
					this.postList = this.postList.concat(res[1].data.data);
					if (res[1].data.current_page === res[1].data.last_page || res[1].data.last_page === 0) {
						this.loadStatus = "nomore";
					} else {
						this.loadStatus = "loadmore"
					}
				})
			},
			getTopicList() {
				this.$H.post('/api/topic/userTopicList/', {
					uid: this.uid
				}).then(res => {
					this.topicList = res[1].data.data;
				})
			},
			getUserInfo() {
				this.$H.post('/api/member/userInfo', {
					uid: this.uid
				}).then(res => {
					this.userInfo = res[1].data;
					uni.setNavigationBarTitle({
						title: this.userInfo.username
					});
				})
			}
		}
	}
</script>
<style lang="scss">
	@import 'ucenter.css';
</style>
