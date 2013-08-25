1.<?php
2./**
3.* 爬虫程序 -- 原型
4.*
5.* BookMoth 2009-02-21
6.*/
7./**
8.* 从给定的url获取html内容
9.*
10.* @param string $url
11.* @return string
12.*/
13.function _getUrlContent($url){
14.$handle = fopen($url, "r");
15.if($handle){
16.$content = stream_get_contents($handle,1024*1024);
17.return $content;
18.}else{
19.return false;
20.}
21.}
22./**
23.* 从html内容中筛选链接
24.*
25.* @param string $web_content
26.* @return array
27.*/
28.function _filterUrl($web_content){
29.$reg_tag_a = '/<[a|A].*?href=[\'\"]{0,1}([^>\'\"\ ]*).*?>/';
30.$result = preg_match_all($reg_tag_a,$web_content,$match_result);
31.if($result){
32.return $match_result[1];
33.}
34.}
35./**
36.* 修正相对路径
37.*
38.* @param string $base_url
39.* @param array $url_list
40.* @return array
41.*/
42.function _reviseUrl($base_url,$url_list){
43.$url_info = parse_url($base_url);
44.$base_url = $url_info["scheme"].'://';
45.if($url_info["user"]&&$url_info["pass"]){
46.$base_url .= $url_info["user"].":".$url_info["pass"]."@";
47.}
48.$base_url .= $url_info["host"];
49.if($url_info["port"]){
50.$base_url .= ":".$url_info["port"];
51.}
52.$base_url .= $url_info["path"];
53.print_r($base_url);
54.if(is_array($url_list)){
55.foreach ($url_list as $url_item) {
56.if(preg_match('/^http/',$url_item)){
57.//已经是完整的url
58.$result[] = $url_item;
59.}else {
60.//不完整的url
61.$real_url = $base_url.'/'.$url_item;
62.$result[] = $real_url;
63.}
64.}
65.return $result;
66.}else {
67.return;
68.}
69.}
70./**
71.* 爬虫
72.*
73.* @param string $url
74.* @return array
75.*/
76.function crawler($url){
77.$content = _getUrlContent($url);
78.if($content){
79.$url_list = _reviseUrl($url,_filterUrl($content));
80.if($url_list){
81.return $url_list;
82.}else {
83.return ;
84.}
85.}else{
86.return ;
87.}
88.}
89./**
90.* 测试用主程序
91.*
92.*/
93.function main(){
94.$current_url = "http://hao123.com/";//初始url
95.$fp_puts = fopen("url.txt","ab");//记录url列表
96.$fp_gets = fopen("url.txt","r");//保存url列表
97.do{
98.$result_url_arr = crawler($current_url);
99.if($result_url_arr){
100.foreach ($result_url_arr as $url) {
101.fputs($fp_puts,$url."\r\n");
102.}
103.}
104.}while ($current_url = fgets($fp_gets,1024));//不断获得url
105.}
106.main();
107.?>
