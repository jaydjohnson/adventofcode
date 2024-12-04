def compute(opcodes, position):
    operation = int(opcodes[position])
    value1_position = int(opcodes[position+1])
    value2_position = int(opcodes[position+2])
    output_position = int(opcodes[position+3])

    if (operation == 1):
        opcodes[output_position] = int(opcodes[value1_position]) + \
            int(opcodes[value2_position])
    elif (operation == 2):
        opcodes[output_position] = int(opcodes[value1_position]) * \
            int(opcodes[value2_position])
    else:
        print('Undefined instruction ', operation)
    
    

def calculate(opcodes):
    for position in range(0, len(opcodes), 4):
        if (opcodes[position] != '99'):
            compute(opcodes, position)
        else:
            print(opcodes[0])
            if (opcodes[0] == 19690720):
                exit()
            return


with open('day2.txt', 'r') as f:
    original_opcodes = f.read().split(',')

for noun in range(0, 100):
    for verb in range(0, 100):
        test_opcodes = original_opcodes.copy()
        test_opcodes[1] = noun
        test_opcodes[2] = verb
        print(noun, verb)
        calculate(test_opcodes)

