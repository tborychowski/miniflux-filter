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
  ports:
    - "3000:3000"
  volumes:
    #- ./logs:/app/logs
    - ./filters.yml:/app/filters.yml
```

## Alternatives
- https://github.com/uggedal/cfg/blob/master/templates/opt/fluxfilter/bin/fluxfilter
- https://github.com/dewey/miniflux-sidekick
