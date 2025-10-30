# Round-Robin Lead Assignment Example

## How the System Works

### Initial Setup
If you have 3 Channel Partners with these round-robin counts:
- CP1: round_robin_count = 1
- CP2: round_robin_count = 2  
- CP3: round_robin_count = 3

### Lead Assignment Pattern
1. **Lead 1** → CP1 (lowest count = 1)
   - CP1's count becomes: 1 + 3 = 4
   - New counts: CP1=4, CP2=2, CP3=3

2. **Lead 2** → CP2 (lowest count = 2)
   - CP2's count becomes: 2 + 3 = 5
   - New counts: CP1=4, CP2=5, CP3=3

3. **Lead 3** → CP3 (lowest count = 3)
   - CP3's count becomes: 3 + 3 = 6
   - New counts: CP1=4, CP2=5, CP3=6

4. **Lead 4** → CP1 (lowest count = 4)
   - CP1's count becomes: 4 + 3 = 7
   - New counts: CP1=7, CP2=5, CP3=6

5. **Lead 5** → CP2 (lowest count = 5)
   - CP2's count becomes: 5 + 3 = 8
   - New counts: CP1=7, CP2=8, CP3=6

6. **Lead 6** → CP3 (lowest count = 6)
   - CP3's count becomes: 6 + 3 = 9
   - New counts: CP1=7, CP2=8, CP3=9

## Pattern: CP1 → CP2 → CP3 → CP1 → CP2 → CP3 → ...

This ensures fair distribution where each Channel Partner gets leads in a rotating pattern.
