const fs = require('fs');
const axios = require('axios');
const YAML = require('yaml');

class Miniflux {

	constructor (host, key, filtersPath) {
		this.req = axios.create({
			baseURL: host + '/v1',
			headers: { 'X-Auth-Token': key }
		});
		this.filters = this.getFilters(filtersPath);
	}

	getFilters (path) {
		const res = fs.readFileSync(path, 'utf8');
		return YAML.parse(res).filters;
	}

	async getEntries () {
		return this.req
			.get('/entries?status=unread')
			.then(res => {
				const {entries} = res.data;
				return entries.map(e => {
					const { id, title, url, feed, content } = e;
					const { feed_url, site_url } = feed;
					return { id, url, title, feed_url, site_url, content };
				});
			});
	}

	async markAsRead (entry_ids = []) {
		return this.req
			.put('/entries', { entry_ids, status: 'read' })
			.then(res => res.status === 204);
	}

}


module.exports = Miniflux;
