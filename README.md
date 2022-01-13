Miniflux Filter
==================
This project "happened" out of frustrations with my RSS feeds posting more and more spam, and the ~~lack of~~ very limited filtering functionality in my favourite rss aggregator - Miniflux.


# Setup with Docker
1. Create a `docker-compose.yml` with the following content

```yml
---
version: '3'
services:
    miniflux-filter:
    image: tborychowski/miniflux-filter:latest
    container_name: miniflux-filter
    restart: unless-stopped
    environment:
        - TZ=Europe/Dublin
        # if not present - there will be no auth
        # - ADMIN_PASSWORD=admin1
        # ERROR, WARNING, INFO, DEBUG
        - LOG_LEVEL=INFO
    ports:
        - "5020:80"
    volumes:
        - ./data:/var/www/html/store
```
2. Run `docker-compose up -d`
3. Open `<serverIP>:5020`


# TODO
- [ ] more settings (caches timeouts, clear caches, etc.)
- [ ] allow to filter all feeds (add `*` to feed list)
- [ ] preview


## Alternatives
- https://github.com/uggedal/cfg/blob/master/templates/opt/fluxfilter/bin/fluxfilter
- https://github.com/dewey/miniflux-sidekick
