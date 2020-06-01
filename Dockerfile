FROM node:alpine
RUN apk add tzdata

WORKDIR /app

COPY . .
RUN npm i

CMD ["node", "index.js"]
