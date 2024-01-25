FROM node:18

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .

RUN cp .env.example .env.local

EXPOSE 3000
CMD npm run dev