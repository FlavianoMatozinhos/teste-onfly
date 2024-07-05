FROM node:14

WORKDIR /app

COPY . .

RUN npm install

RUN npm install axios vue-pagination-2 moment

RUN npm run build

EXPOSE 8080

CMD ["npm", "run", "serve"]
