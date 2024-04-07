FROM node:latest

RUN mkdir /nuxt && chown node:node /nuxt

WORKDIR /nuxt

USER node

COPY --chown=node:node package*.json ./
RUN npm install
COPY --chown=node:node . .

EXPOSE 3000
CMD npm run dev