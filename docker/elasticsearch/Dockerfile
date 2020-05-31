FROM elasticsearch:7.7.0

# Get elasticsearch pinyin ik plugins
ENV PLUGIN_IK_VERSION=7.7.0
ENV PLUGIN_PINYIN_VERSION=7.7.0
RUN elasticsearch-plugin install -b https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v${PLUGIN_IK_VERSION}/elasticsearch-analysis-ik-${PLUGIN_IK_VERSION}.zip
RUN elasticsearch-plugin install -b https://github.com/medcl/elasticsearch-analysis-pinyin/releases/download/v${PLUGIN_PINYIN_VERSION}/elasticsearch-analysis-pinyin-${PLUGIN_PINYIN_VERSION}.zip

# No need to put any yml for analyzer
COPY IKAnalyzer.cfg.xml /usr/share/elasticsearch/config/analysis-ik/
COPY mydict.dic /usr/share/elasticsearch/config/analysis-ik/custom/

# COPY elasticsearch.yml /usr/share/elasticsearch/config/elasticsearch.yml
# COPY roles.yml /usr/share/elasticsearch/config/x-pack/roles.yml
