import csv

def get_offsets(wires):
    x_min = 0
    x_max = 0
    x_current = 0
    y_min = 0
    y_max = 0
    y_current = 0

    for wire in wires:
        for instruction in wire:
            direction = instruction[0]
            length = int(instruction[1:])
            if (direction == 'L'):
                x_current -= length
                if (x_current < x_min):
                    x_min = x_current
            if (direction == 'R'):
                x_current += length
                if (x_current > x_max):
                    x_max = x_current
            if (direction == 'D'):
                y_current -= length
                if (y_current < y_min):
                    y_min = y_current
            if (direction == 'U'):
                y_current += length
                if (y_current > y_max):
                    y_max = y_current
    return [x_min, x_max, y_min, y_max]

with open('day3.txt', 'r') as f:
    wires = list(csv.reader(f))

offsets = get_offsets(wires)
print(offsets[1]-offsets[0])
panel = [[0 for x in range(offsets[1]-offsets[0])] for y in range(offsets[3]-offsets[2])]
panel[10][10] = '1'
print(panel[10][10])
print(panel[0][0])
