FROM node:alpine3.16
ARG backend_port

ENV BACKEND_PORT=$backend_port

WORKDIR /app

COPY package.json ./
COPY package-lock.json ./

RUN npm install

COPY . .

CMD ["npm", "start"]
