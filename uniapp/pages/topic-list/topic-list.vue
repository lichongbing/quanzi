<template>
	<view>
		<u-tabs name="tab_name" :list="topicCate" active-color="#dd524d" :is-scroll="false" c :current="current" @change="tabChange"></u-tabs>
		<list :list="topicList" :loadStatus="loadStatus"></list>
	</view>
</template>

<script>
	let page;
	import list from '../../components/topic-list/topic-list.vue';
	export default {
		components: {
			list
		},
		data() {
			return {
				current: 0,
				topicCate: [],
				topicCateId: 0,
				topicList: [],
				loadStatus: "loading"
			};
		},
		onLoad() {
			page = 1;
			this.getTopicCate();
			this.getTopicList();
		},
		onReachBottom() {
			page++;
			this.getTopicList();
		},
		methods: {
			tabChange(index) {
				page = 1;
				this.current = index;
				this.topicList = [];				
				this.loadStatus = "loading";
				this.topicCateId = this.topicCate[index].id
				this.getTopicList();
			},
			getTopicCate() {
				this.$H.post("/api/topic/cateList")
					.then(res => {
						this.topicCate = res[1].data;
					})
			},
			getTopicList() {
				this.$H.post('/api/topic/cateTopic/?page=' + page, {
					id: this.topicCateId
				}).then(res => {
					this.topicList = this.topicList.concat(res[1].data.data);
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
	page {
		background-color: #f5f5f5;
	}
</style>
