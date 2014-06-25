cat > tmp.txt <<EOT
{
    "id": "1",
    "token": "6e7ce41be600319bad04d877facc33a4c5dba6ef5a51e73d82544cd9eda0da6e",
    "requete": "lister_sondage_en_cours"
}
EOT
cat tmp.txt | tr -d '\n' | tr -d ' ' && echo 