def calculate_fuel(mass):
    fuel = int(mass / 3) - 2
    if (fuel < 9):
        return fuel
    else:
        fuel += calculate_fuel(fuel)
        return fuel
    

total_fuel = 0

with open('day1_in.txt', 'r') as f:
    modules = f.read().splitlines()
    for module in modules:
        print(calculate_fuel(int(module)))
        total_fuel = total_fuel + calculate_fuel(int(module))

print(total_fuel)

print(calculate_fuel(12))
print(calculate_fuel(14))
print(calculate_fuel(1969))
print(calculate_fuel(100756))
