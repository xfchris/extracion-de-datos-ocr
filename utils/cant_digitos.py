import math


def number_of_digits(detect_number):
    if detect_number > 0:
        quantity = int(math.log10(detect_number))+1
    elif detect_number == 0:
        quantity = 1
    elif detect_number < 0:
        quantity = int(math.log10(-detect_number))+1
    return quantity
