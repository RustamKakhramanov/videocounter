## VideoCounter


Для продакшен
```
ffmpeg -i "rtsp://admin:itON2018@95.143.15.218/cam/realmonitor?channel=1&subtype=1" -nostdin -nostats -vf fps=1 storage/app/public/raw/out%010d.jpg > /dev/null 2>&1 < /dev/null &
```

Для отладки
```
ffmpeg -i demo.dav -vf fps=1 storage/app/public/raw/out%010d.jpg
```

...