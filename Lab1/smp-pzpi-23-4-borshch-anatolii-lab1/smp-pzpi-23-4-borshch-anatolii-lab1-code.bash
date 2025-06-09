#!/bin/bash                                                                                                                                                                                                         pzpi-23-4-borshch-anatolii-task2                                                                                                                                                                                                                           #!/bin/bash

VERSION="1.0"
SCRIPT_NAME="$(basename "$0")"

function print_help {
        echo "Синтаксис:"
        echo "  $SCRIPT_NAME [--help | --version] | [[-q|--quiet] [академ_група] файл_із_cist.csv]"
        echo
        echo "Ключі:"
        echo "  --help        Вивести цю довідку та завершити роботу"
        echo "  --version     Вивести версію скрипта та завершити роботу"
        echo "  -q, --quiet   Не виводити інформацію у стандартний потік виведення"
}

function print_version {
        echo "$SCRIPT_NAME version $VERSION"
}

quiet_mode="false"
group=""
file=""
for arg in "$@"; do
        if [[ "$arg" == "--help" ]]; then
                print_help
        exit 0
        elif [[ "$arg" == "--version" ]]; then
                print_version
        exit 0
        elif [[ "$arg" == "-q" || "$arg" == "--quiet" ]]; then
                quiet_mode=true
        elif [[ "$arg" == *.csv ]]; then
                file="$arg"
        elif [[ "$arg" =~ ^ПЗПІ-23-[1-5]$ ]]; then
                group="$arg"
        fi
done

if [[ -z "$file" ]]; then
        echo "Виберіть CSV файл:"
        select file in TimeTable_??_??_20??.csv "QUIT"; do
                if [[ "$file" == "QUIT" ]]; then
                        exit 1
                elif [[ -n "$file" && "$file" != "QUIT" ]]; then
                break
                else
                        echo "Невірний вибір, спробуйте ще раз."
                fi
        done
else
        if [[ ! -f "$file" ]]; then
                echo "Файл \"$file\" не знайдено."
                exit 1
        fi
fi

if [[ -z "$group" ]]; then
        echo "Виберіть групу:"
        select group in "ПЗПІ-23-1" "ПЗПІ-23-2" "ПЗПІ-23-3" "ПЗПІ-23-4" "ПЗПІ-23-5" "QUIT"; do
                if [[ "$group" == "QUIT" ]]; then
                        echo "Вихід"
                exit 0
                elif [[ -n "$group" ]]; then
                break
                else
                        echo "Невірний вибір, спробуйте ще раз."
                fi
        done
fi

output_file="Google_${file}"
output_file="${output_file%.csv}.csv"

> "$output_file"

echo "Subject,Description,Start Date,Start Time,End Time" > "$output_file"

sed 's/\r/ end\n/g' "$file" |
iconv -f cp1251 -t utf8 |
awk 'BEGIN {
        FPAT="[^,]*|\"[^\"]*\""
}
{
        dt = $2
        tm = $3

        gsub(/"/, "", dt)
        gsub(/"/, "", tm)

        split(dt, d, ".")
        split(tm, t, ":")

        if (length(d[1]) && length(d[2]) && length(d[3]) &&
                length(t[1]) && length(t[2]) && length(t[3])) {
                key = sprintf("%04d%02d%02d%02d%02d%02d", d[3], d[2], d[1], t[1], t[2], t[3])
                print key "," $0
        }
}' |
sort -t, -k1,1 |
cut -d, -f2- |
awk -v group="$group" -v quiet="$quiet_mode" -v output="$output_file" '
BEGIN {
        FPAT="[^,]*|\"[^\"]*\""
}
{
        gsub(/"/, "", $1);
        gsub(/"/, "", $2);
        gsub(/"/, "", $3);
        gsub(/"/, "", $5);
        gsub(/"/, "", $12);

        if ($1 ~ ("^" group)) {
                sub("^" group " - ", "", $1);

                split($2, d1, ".")
                split($3, tb, ":")
                split($5, te, ":")

                hb = tb[1] + 0
                mb = tb[2]
                ampm_b = (hb >= 12 ? "PM" : "AM")
                if (hb > 12) hb -= 12
                if (hb == 0) hb = 12
                start_time = sprintf("%02d:%s %s", hb, mb, ampm_b)

                he = te[1] + 0
                me = te[2]
                ampm_e = (he >= 12 ? "PM" : "AM")
                if (he > 12) he -= 12
                if (he == 0) he = 12
                end_time = sprintf("%02d:%s %s", he, me, ampm_e)

                date = sprintf("%02d/%02d/%04d", d1[2], d1[1], d1[3])

                split($1, parts, / *; */)
                delete subjects
                delete group_map
                for (i in parts) {
                        item = parts[i]
                        gsub(/^ +| +$/, "", item)
                        if (item == "") continue
                        match(item, /[A-Za-zА-Яа-яЁёІіЇїЄєҐґЃѓ]+/, m)
                        if (m[0] != "") {
                                key = m[0]
                                group_map[key] = (key in group_map) ? group_map[key] ";" item : item
                        }
                }
                idx = 0
                for (k in group_map) {
                        idx++
                        subjects[idx] = group_map[k]
                }

                for (i = 1; i <= idx; i++) {
                        subject = subjects[i]
                        gsub(/^ +| +$/, "", subject)
                        if (subject == "") continue

                        key = "\"" subject " " $1 "\""
                        if(subject ~/Лб/){
                                labsAdded[subject]++
                                number = int((labsAdded[subject] + 1) / 2)
                        } else {
                                count[subject]++
                                number = count[subject]
                        }

                        numed_subject = subject "; №" number

                        if (quiet != "true") {
                                printf("%s, %s ,%s, %s, %s,\n", numed_subject, $12, date, start_time, end_time)
                        }
                        printf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n", numed_subject, $12, date, start_time, end_time) >> output
                }
        }
}'
