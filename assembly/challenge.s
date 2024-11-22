flag:
        .word   -559038737
.LC0:
        .ascii  "Nice solve!\000"
main:
        save    16,$fp,$ra,$gp
        addiu   $fp,$sp,-4080
        lapc    $gp,_gp
        lw      $a3,%got_page(flag)($gp)
        lw      $a3,%got_ofst(flag)($a3)
        andi    $a2,$a3,0xffff
        li      $a3,61469
        beqc    $a2,$a3,.L2
        li      $a3,-1                  # 0xffffffffffffffff
        bc      .L3
.L2:
        lw      $a3,%got_page(flag)($gp)
        lw      $a3,%got_ofst(flag)($a3)
        sra     $a2,$a3,16
        li      $a3,23727
        beqc    $a2,$a3,.L4
        li      $a3,-1                  # 0xffffffffffffffff
        bc      .L3
.L4:
        lapc.h  $a0,.LC0
        lw      $a3,%got_call(puts)($gp)
1:      jalrc       $a3
        move    $a3,$zero
.L3:
        move    $a0,$a3
        restore.jrc     16,$fp,$ra,$gp