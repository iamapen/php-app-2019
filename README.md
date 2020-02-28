



## docker

起動
```bash
cd docker

docker-compose up -d
docker-compose ps
```

DBサーバのシェルにアクセス
```bash
docker-compose exec db bin/bash
```

停止
```bash
docker-compose stop
```

削除
```bash
docker-compose down
```
