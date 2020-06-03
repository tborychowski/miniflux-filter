require('dotenv').config();
const {Miniflux, logger, slack, match} = require('./lib');
const {HOST, API_KEY, CHECK_EVERY_S} = process.env;
const filtersPath = __dirname + '/filters.yml';
const mini = new Miniflux(HOST, API_KEY, filtersPath);

function filter () {
	logger.info('Checking filters...');
	mini
		.getEntries()
		.then(res => {
			if (!Array.isArray(res)) {
				return logger.error('Could not find entries. API response incorrect', res);
			}
			return res.filter(item => match(item, mini.filters));
		})
		.then(matched => {
			const l = matched.length;
			if (!l) return logger.info('No items to filter out.');
			const plural = l > 1 ? 's' : '';
			logger.info(`${l} item${plural} to filter out`);
			logger.info(JSON.stringify(matched));

			slack(matched);

			const ids = matched.map(item => item.id);
			return mini.markAsRead(ids);
		})
		.then(res => {
			if (!res) logger.error('Could not mark as read.');
		})
		.catch(e => logger.error(e.toString()));
}

setInterval(filter, (CHECK_EVERY_S || 3) * 1000);
filter();
