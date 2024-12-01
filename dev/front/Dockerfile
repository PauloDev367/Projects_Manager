FROM node:18.4.0

WORKDIR /app

COPY ./front/package*.json ./

RUN npm install

COPY ./front /app/

EXPOSE 8080

CMD ["npm", "run", "dev"]
