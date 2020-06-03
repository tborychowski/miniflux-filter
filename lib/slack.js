const axios = require('axios');

const COLORS = {
	error: '#8b4848',
	warning: '#af8a1a',
	success: '#408062'
};


function notify (matched = []) {
	if (!process.env.SLACK_HOOK) return;

	const plural = matched.length > 1 ? 's' : '';
	const prettyPrint = matched.map(item => `<${item.url}|${item.title}>`).join('\n- ');
	const text = `Filtered out ${matched.length} article${plural}: \n- ${prettyPrint}`;
	const attachments = [
		{ color: COLORS.success, pretext: '*Miniflux Filter*', text}
	];
	axios.post(process.env.SLACK_HOOK, {attachments});
}

module.exports = notify;
