Miniflux Filter
==================

# Setup
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

# TODO
- [x] filter create date/update date
- [x] if feeds file is older than 1h - reload
- [x] proper logging
- [x] feed icons with caching

- [x] filtering script
  - [x] counters
  - [x] cron job

- [x] export
- [x] import
- [x] button to "run all now"
- [x] run all after adding/editing a filter
- [x] log rotate (clear log on container reboot)

- [x] docker image
  - [x] multiplatform
  - [x] publish
  - [ ] move to github?
  - [ ] automate building & publishing

- [x] longer cache timeouts
- [x] button to reload feed data
- [x] silence apache logging

- [x] admin pass from ENV
- [x] log level from ENV
- [x] "remember me" option to save cookie for 30 days

- [ ] move code to miniflux-filter
- [ ] more settings (log level, caches timeouts, clear caches, etc.)
- [ ] allow to filter all feeds (add `*` to feed list)
- [ ] sqlite db?
- [ ] preview
