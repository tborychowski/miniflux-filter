require('dotenv').config();
const {Miniflux, logger, slack, match} = require('./lib');
const {HOST, API_KEY, CHECK_EVERY_S, LOG_FILTERED_ONLY} = process.env;
const filtersPath = __dirname + '/filters.yml';
const mini = new Miniflux(HOST, API_KEY, filtersPath);

function filter () {
	if (!LOG_FILTERED_ONLY) logger.info('Checking filters...');
	mini
		.getEntries()
		.then(res => {
			if (!Array.isArray(res)) {
				return logger.error('Could not find entries. API response incorrect', res);
			}
			return res.filter(item => match(item, mini.filters));
		})
		.then(matched => {
			const count = matched.length;
			if (!count) {
				if (!LOG_FILTERED_ONLY) logger.info('No items to filter out.');
				return true;
			}
			const plural = count > 1 ? 's' : '';
			logger.info(`${count} item${plural} to filter out`);
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
