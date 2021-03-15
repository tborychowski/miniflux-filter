const Miniflux = require('./miniflux');
const logger = require('./logger');
const slack = require('./slack');

function match (item, filters) {
	for (let filter of filters) {
		let toMatch = Object.keys(filter).length;
		if (filter.title) {
			if (item.title.toLowerCase().includes(filter.title.toLowerCase())) toMatch -= 1;
		}
		if (filter.content) {
			if (item.content.toLowerCase().includes(filter.content.toLowerCase())) toMatch -= 1;
		}
		if (filter.url) {
			if (item.url.includes(filter.url)) toMatch -= 1;
			else if (item.site_url.includes(filter.url)) toMatch -= 1;
			else if (item.feed_url.includes(filter.url)) toMatch -= 1;
		}
		if (toMatch === 0) return true;
	}
	return false;
}


module.exports = {
	Miniflux,
	logger,
	slack,
	match,
};
