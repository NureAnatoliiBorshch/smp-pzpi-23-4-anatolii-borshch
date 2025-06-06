#!/bin/bash
draw_tree(){

local height=$1
local snow_width=$2
local branch_height=$((height / 2))
local layer_type=0

if((height <= 0 || snow_width <= 0)); then
echo "ПОМИЛКА! Висота та ширина повинні бути додатніми" >&2
exit 1
fi

if(((height < 8) || (snow_width < 7))); then
echo "ПОМИЛКА! Невірні дані." >&2
exit 2
fi

local dif=$((height - snow_width))
if (( (dif < 0) || (dif > 2))); then
echo "ПОМИЛКА! Невірні дані." >&2
exit 3
fi

if (( height % 2 != 0  && snow_width % 2 != 0)); then
if ((dif == 0)); then
echo "ПОМИЛКА! НЕ можливо побудувати ялинку!" >&2
exit 4
fi
fi

if ((height % 2 ==0 && snow_width % 2 == 0)); then
if (( dif == 2)); then
echo "ПОМИЛКА! НЕ можливо побудувати ялинку!" >&2
exit 5
fi
fi

if ((snow_width % 2 ==0)); then
((snow_width--))
fi

draw_branch(){
local i=$1
local stars=$((2 * i + 1))
local spaces=$((snow_width / 2 - i))
printf "%${spaces}s" ""

for((j=0; j < stars; j++)); do
if((layer_type % 2 == 0)); then
printf "*"
else
printf "#"
fi
done
printf "\n"
}

local i=0
while((i < branch_height - 1)); do
draw_branch $i
((layer_type++))
((i++))
Done

local i=1
until ((i >= branch_height - 1)); do
draw_branch $i
((layer_type++))
((i++))
done

for line in 1 2; do
printf "%$((snow_width / 2 - 3 / 2))s" ""

for((j=0; j < 3; j++)); do
printf "#"
done
printf "\n"
done

for((i=0; i < snow_width; i++)); do
printf "*"
done

printf "\n"
}

if(( $# != 2)); then
echo "ПОМИЛКА! Потрібно 2 параметри!" >&2
exit 1
fi

height=$(($1))
snow_width=$(($2))

draw_tree $height $snow_width
