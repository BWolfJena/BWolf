FROM node:8-alpine

RUN apk add --no-cache tini python build-base

# Create app directory
RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

# Install app dependencies
COPY package.json ./
COPY yarn.lock ./
RUN yarn install --frozen-lockfile --non-interactive

EXPOSE 80

ENTRYPOINT ["/sbin/tini", "--"]
CMD ["yarn", "start"]