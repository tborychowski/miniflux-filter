# miniflux filter
This project "happened" out of frustrations with my RSS feeds posting more and more spam, and the lact of filtering functionality in my favourite rss aggregator - Miniflux.

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
    volumes:
      #- ./logs:/app/logs
      - ./filters.yml:/app/filters.yml

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
- AND article's title includes the filter `title`
Such an article is marked as read.


## Alternatives
- https://github.com/uggedal/cfg/blob/master/templates/opt/fluxfilter/bin/fluxfilter
- https://github.com/dewey/miniflux-sidekick
