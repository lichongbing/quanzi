import config from './config.js';
export default {
	domain:config.domain,
	miniappName:config.miniappName,
	request(options = {}) {
		return uni.request(options);
	},
	post(url, data = {}, options = {}) {
		options.method = 'POST'
		options.url = config.domain + url
		options.data = data
		options.header = {
			'content-type': 'application/x-www-form-urlencoded',
		}
		return this.request(options)
	}
}
