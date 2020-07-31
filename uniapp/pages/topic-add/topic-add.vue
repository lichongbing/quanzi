<template>
	<view>
		<view class="container">
			<u-form ref="uForm">
				<u-form-item label="圈子名称" label-width="auto">
					<u-input v-model="name" placeholder="必填*" />
				</u-form-item>
				<u-form-item label="圈子描述" label-width="auto">
					<u-input v-model="desc" placeholder="必填*" />
				</u-form-item>
				<u-form-item label="类别">
					<u-input v-model="cateName" @click="show = true" placeholder="请选择" type="select" />
				</u-form-item>
			</u-form>
		</view>
		<view class="container">
			<u-upload ref="uUpload" name="Image" :action="uploadImgUrl" max-count="1" upload-text="圈子封面" @on-uploaded="submit"
			 :auto-upload="false" :file-list="thumbImg"></u-upload>
		</view>
		<!-- 分类选择器 -->
		<u-select v-model="show" value-name='id' label-name='name' mode="single-column" :list="cateList" @confirm="confirm"></u-select>
		<!-- 提交按钮 -->
		<u-button @click="onUploaded" class="fixed-bottom" type="error" shape="circle">提交</u-button>
		<u-toast ref="uToast" />
	</view>
</template>

<script>
	export default {
		data() {
			return {
				show: false,
				cateList: [],
				name: '',
				desc: '',
				cateName:'',
				uploadImgUrl: '',
				action: "",
				thumbImg: '',
				cateId: ''
			};
		},
		onLoad() {
			this.uploadImgUrl = this.$H.domain + '/admin/index/upload';
			this.getCate();
		},
		methods: {
			onUploaded() {
				uni.showLoading({
					mask: true,
					title: "提交中..."
				})
				this.$refs.uUpload.upload();
			},
			submit(e) {
				this.thumbImg = e[0].response.url[0];
				
				if (this.name == '' || this.thumbImg == '' || this.cateId == '') {
					this.$refs.uToast.show({
						title: '必填项不能为空',
						type: 'error'
					})
					uni.hideLoading();
					return;
				}				
				this.$H.post('/api/topic/topicAdd', {
					sessionKey: uni.getStorageSync('sessionKey'),
					title: this.name,
					imgUrl: this.thumbImg,
					cateId: this.cateId
				}).then(res => {
					if (res[1].data.code === 1) {
						this.$refs.uToast.show({
							title: '成功创建圈子',
							type: 'success',
							url: '/pages/topic-detail/topic-detail?id=' + res[1].data.id
						})
					} else {
						this.$refs.uToast.show({
							title: '创建圈子失败',
							type: 'error'
						})
					}
				})
			},
			getCate() {
				this.$H.post('/api/topic/cateList').then(res => {
					this.cateList = res[1].data;
				})
			},
			confirm(e) {
				this.cateId = e[0].value;
				this.cateName = e[0].label;
			}
		}
	}
</script>

<style lang="scss">
	@import 'topic-add.css';
</style>
