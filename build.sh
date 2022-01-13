#!/usr/bin/env bash

# docker buildx build --push --platform linux/arm/v7,linux/arm64/v8,linux/amd64 -t tborychowski/miniflux-filter:latest -t tborychowski/miniflux-filter:2.0.0 .
docker build --tag tborychowski/miniflux-filter:latest .
