<template>
	<view>
		<view class="detailArticle">
			<view class="user-item">
				<image :src="postDetail.userInfo.avatar"></image>
				<view class="user-item-user">
					<text class="user-name">{{postDetail.userInfo.username}}</text>
					<view class="cxplain">{{postDetail.userInfo.intro}}</view>
				</view>
				<block v-if="isFollow">
					<u-button size="mini" style="float:right;font-size: 14px;" @click="cancelFollow">已关注</u-button>
				</block>
				<block v-else>
					<u-button type="error" size="mini" style="float:right;font-size: 14px;" @click="follow">关注</u-button>
				</block>
			</view>
			<view class="icon">
				<text>{{postDetail.create_time | timeFrom}}</text>
			</view>
			<view class="hr"></view>
			<text selectable="true">{{postDetail.content}}</text>
			<!--一张图片-->
			<block v-if="postDetail.media.length == 1">
				<image class="img-style-1" @tap.stop="previewImage" mode="aspectFill" :data-current="postDetail.media[0]"
				 :data-image="postDetail.media" :src="postDetail.media[0]"></image>
			</block>
			<!--二张图片-->
			<block v-if="postDetail.media.length == 2">
				<view class="img-style-2">
					<image v-for="(mediaItem, index2) in postDetail.media" :key="index2" @tap.stop="previewImage" mode="aspectFill"
					 :data-current="mediaItem" :data-image="postDetail.media" :src="mediaItem"></image>
				</view>
			</block>
			<!--三张以上图片-->
			<block v-if="postDetail.media.length > 2">
				<view class="img-style-3">
					<image v-for="(mediaItem, index2) in postDetail.media" :key="index2" @tap.stop="previewImage" mode="aspectFill"
					 :data-current="mediaItem" :data-image="postDetail.media" :src="mediaItem"></image>
				</view>
			</block>
			<!--按钮-->
			<view class="btn-view">
				<block v-if="isCollection">
					<u-button @click="cancelCollection">
						<text class="iconfont icon-lujing" style="color: #d81e06;"></text>喜欢
					</u-button>
				</block>
				<block v-else>
					<u-button @click="addCollection">
						<text class="iconfont icon-shoucang"></text>喜欢
					</u-button>
				</block>
				<block v-if="isThumb">
					<u-button @click="cancelThumb" type="default">
						<text class="iconfont icon-dianzan" style="color: #d81e06;"></text>点赞
					</u-button>
				</block>
				<block v-else>
					<u-button @click="addThumb" type="default">
						<text class="iconfont icon-dianzan1"></text>点赞
					</u-button>
				</block>
				<u-button open-type="share">
					<text class="iconfont icon-fenxiang"></text>分享
				</u-button>
			</view>
			<view class="divider">
				<u-divider>精彩评论</u-divider>
			</view>
			<block v-for="(item, index) in postDetail.comment" :key="index">
				<view class="comment-item">
					<image class="avatar" :src="item.userInfo.avatar"></image>
					<view class="c-content">
						<text>{{item.userInfo.username}}</text>
						<text class="c-txt">{{item.content}}</text>
					</view>
					<text class="time">{{item.create_time|timeFrom}}</text>
				</view>
			</block>
			<block v-if="postDetail.comment.length == 0">
				<u-empty text="暂无评论" mode="message"></u-empty>
			</block>
		</view>
		<!-- 评论输入框 -->
		<view class="comment-tool">
			<textarea placeholder="吐个槽..." fixed="true" cursor-spacing="10" v-model="cTxt" auto-height="true" placeholder-class="txt-placeholder"></textarea>
			<u-button type="error" @click="addComment" :disabled="isSubmitD" style="border-radius: 0;">发布</u-button>
		</view>
		<!-- 提示弹窗 -->
		<u-toast ref="uToast" />
	</view>
</template>

<script>
	export default {
		data() {
			return {
				postId: 0,
				postDetail: {},
				isFollow: false,
				isCollection: false,
				isThumb: false,
				cTxt: "",
				isSubmitD:false
			};
		},
		onLoad(options) {
			this.postId = options.id;
			this.getPostDetail();
			this.getIsCollection();
			this.getIsThumb();
			
			//#ifdef MP-WEIXIN
			wx.showShareMenu({
				withShareTicket: true,
				menus: ['shareAppMessage', 'shareTimeline']
			})
			//#endif
		},
		onShareAppMessage(res) {
			if (res.from === 'button') { // 来自页面内分享按钮
				console.log(res.target)
			}
			let imgURL = this.$H.domain + "/uploads/default/wx_share_cover.jpg";
			if (this.postDetail.media.length > 0) {
				imgURL = this.postDetail.media[0];
			}
			return {
				title: this.postDetail.content,
				path: '/pages/post-detail/post-detail?id=' + this.postId,
				imageUrl: imgURL
			}
		},
		methods: {
			addComment() {
				this.isSubmitD = true;
				if (uni.getStorageSync("sessionKey") == "") {
					uni.navigateTo({
						url: '/pages/login/login'
					})
					this.isSubmitD = false;
					return;
				}

				if (this.cTxt == "") {
					this.$refs.uToast.show({
						title: '评论不能为空',
						type: 'error'
					})
					this.isSubmitD = false;
					return;
				}
				this.$H.post('/api/comment/addComment', {
					content: this.cTxt,
					sessionKey: uni.getStorageSync("sessionKey"),
					uid: this.postDetail.uid,
					aid: this.postId
				}).then(res => {
					if (res[1].data.code === 1) {
						this.cTxt = "";
						this.$refs.uToast.show({
							title: '评论成功',
							type: 'success'
						})
						this.getPostDetail();						
					}
					this.isSubmitD = false;
				});
			},
			getPostDetail() {
				this.$H.post('/api/post/detail?id=' + this.postId).then(res => {
					this.postDetail = res[1].data;
					this.getIsFollow();
				});
			},
			getIsFollow() {
				this.$H.post('/api/member/isFollow', {
					sessionKey: wx.getStorageSync("sessionKey"),
					id: this.postDetail.uid
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isFollow = true
					} else if (res[1].data.code === 0) {
						this.isFollow = false
					}
				});
			},
			getIsCollection() {
				this.$H.post('/api/post/isCollection', {
					sessionKey: wx.getStorageSync("sessionKey"),
					id: this.postId
				}).then(res => {
					if (res[1].data.code === 0) {
						this.isCollection = true
					} else if (res[1].data.code === 1) {
						this.isCollection = false
					}
				});
			},
			getIsThumb() {
				this.$H.post('/api/post/isThumb', {
					sessionKey: wx.getStorageSync("sessionKey"),
					id: this.postId
				}).then(res => {
					if (res[1].data.code === 0) {
						this.isThumb = true
					} else if (res[1].data.code === 1) {
						this.isThumb = false
					}
				});
			},
			cancelCollection() {
				this.$H.post('/api/post/cancelCollection', {
					sessionKey: uni.getStorageSync("sessionKey"),
					id: this.postId
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isCollection = false;
					}
				})
			},
			addCollection() {
				this.$H.post('/api/post/addCollection', {
					sessionKey: uni.getStorageSync("sessionKey"),
					id: this.postId,
					uid: this.postDetail.uid
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isCollection = true;
					}
				})
			},
			addThumb() {
				this.$H.post('/api/post/addThumb', {
					sessionKey: uni.getStorageSync("sessionKey"),
					id: this.postId,
					uid: this.postDetail.uid
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isThumb = true;
					}
				})
			},
			cancelThumb() {
				this.$H.post('/api/post/cancelThumb', {
					sessionKey: uni.getStorageSync("sessionKey"),
					id: this.postId,
					uid: this.postDetail.uid
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isThumb = false;
					}
				})
			},
			follow() {
				this.$H.post('/api/member/addFollow', {
					sessionKey: uni.getStorageSync("sessionKey"),
					id: this.postDetail.uid
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isFollow = true;
					} else {
						this.$refs.uToast.show({
							title: res[1].data.msg,
							type: 'error'
						})
					}
				})
			},
			cancelFollow() {
				this.$H.post('/api/member/cancelFollow', {
					sessionKey: uni.getStorageSync("sessionKey"),
					id: this.postDetail.uid
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isFollow = false;
					}
				})
			},
			previewImage(e) {
				uni.previewImage({
					current: e.currentTarget.dataset.current, // 当前显示图片的http链接
					urls: e.currentTarget.dataset.image // 需要预览的图片http链接列表
				})
			}
		}
	}
</script>

<style lang="scss">
	@import './post-detail.css'
</style>
