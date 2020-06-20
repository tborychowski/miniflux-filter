# miniflux filter
This project "happened" out of frustrations with my RSS feeds posting more and more spam, and the lack of filtering functionality in my favourite rss aggregator - Miniflux.

## Docker
`docker-compose.yml`
```yml
---
version: '3'
services:
  miniflux-filter:
    image: tborychowski/miniflux-filter
    container_name: miniflux-filter
    restart: unless-stopped
    environment:
      - HOST=https://rss.domain.tld
      - API_KEY=<asdASD123>
      - CHECK_EVERY_S=300 # 300 seconds = 5 min
      - SLACK_HOOK=https://hooks.slack.com/services/123...
    volumes:
      #- ./logs:/app/logs
      - ./filters.yml:/app/filters.yml

```

## No Docker (run locally with node)
### Pre-requisities
- [nodejs](https://nodejs.org/en/) installed (preferably the LTS version)

### Steps
1. Open terminal and `cd` to your preferred directory, then run:
```sh
git clone https://github.com/tborychowski/miniflux-filter.git
cd miniflux-filter
npm install
mv .env-sample .env
```
2. Then edit `.env` file with your settings (see details below), and run:
```sh
node index.js
```

### How to run it in the background (so you don't have to keep terminal open)
1. Install pm2 (or any alternative node process manager):
```sh
npm install pm2 -g
```
2. Commands:
```sh
# start miniflux-filter
pm2 start index.js --name miniflux-filter

# list running apps
pm2 list

# stop miniflux-filter
pm2 stop miniflux-filter

# delete miniflux-filter from the pm2 list
pm2 del miniflux-filter

# launch pm2 & apps on boot
pm2 startup

# remove the launch-on-boot
pm2 unstartup
```

## ENV variables
There are some environmental variables that the `miniflux-filter` needs:
```sh
HOST=https://rss.domain.tld           # url to your miniflux instance
API_KEY=<shB6Zo2ds>                   # API key generated in your miniflux instance
CHECK_EVERY_S=300                     # Frequency (in seconds) to run the filter
SLACK_HOOK=https://hooks.slack.com... # [optional] slack webhook URL for notifications
```



## Filters
Create `filters.yml` file (or copy the attached one).
Format is very simple:
```yml
filters:
  - url: 'my-rss-url.com'  # matches url of the article, rss feed, or the site
    title: 'windows 10'    # a keyword of a phrase to look for in articles' titles
  - url: 'my-rss-url.com'  # same url can be repeated with different filters
    title: '[sponsored]'   # there's just one keyword per 1 filter item
```
If an item is "matched", i.e.:
- url of the article, rss feed or the site includes the one from the filter
- AND article's title includes the filter `title` - such an article is marked as read
- If you provide a `SLACK_HOOK` url, you'll also get a notification with filtered-out articles and links, just for reference :-)

## Alternatives
- https://github.com/uggedal/cfg/blob/master/templates/opt/fluxfilter/bin/fluxfilter
- https://github.com/dewey/miniflux-sidekick
