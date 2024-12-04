def arrangement_count(p, g, springs, groups):
    #print('Starting Count ' + str(p) + ' ' + str(g))
    if g >= len(groups): # no more groups
        if p < len(springs) and '#' in springs[p:]:
            # eg: .##?????#.. 4,1
            return 0 # not a solution - there are still damaged springs in the record
        return 1
    
    if p >= len(springs):
        return 0 # we ran out of springs but there are still groups to arrange

    res = None
    gs = groups[g] # damaged group size

    print(f"Starting {p} {g} {gs}")
    if springs[p] == '?':
        # if we can start group of damaged springs here
        # eg: '??#...... 3' we can place 3 '#' and there is '?' or '.' after the group
        # eg: '??##...... 3' we cannot place 3 '#' here
        #print(f"current_group: {springs[p:p+gs]} last spring: {springs[p+gs]}")
        if '.' not in springs[p:p + gs] and springs[p + gs] != '#':
            # start damaged group here + this spring is operational ('.')
            res = arrangement_count(p + gs + 1, g + 1, springs, groups) + arrangement_count(p + 1, g, springs, groups)
            print('A', res)
        else:
            # this spring is operational ('.')
            res = arrangement_count(p + 1, g, springs, groups)
            print('B', res)
    elif springs[p] == '#':
        # if we can start damaged group here
        #print(f"current_group: {springs[p:p+gs]} last spring: {springs[p+gs]}")
        if '.' not in springs[p:p + gs] and springs[p + gs] != '#':
            res = arrangement_count(p + gs + 1, g + 1, springs, groups)
            print('C', res)
        else:
            res = 0 # not a solution - we must always start damaged group here
            print('D', res)
    elif springs[p] == '.':
        res = arrangement_count(p+1, g, springs, groups) # operational spring -> go to the next spring
        print('E', res)

    return res

with open('day12.ex') as f:
    sum_of_arrangements = 0

    for line in f.readlines():
        springs, groups = line.split()

        groups = list(map(int, groups.split(',')))
        springs = springs + '.' # make sure there is operational spring after each damaged group

        # print(springs)
        # print(groups)
        print(" ----- new group ----- ")
        sum = arrangement_count(0, 0, springs, groups)
        print(f"Sum for group: {sum}")
        sum_of_arrangements += sum

    print(sum_of_arrangements)