input:
        .string "XXXXXXXXXXX"
secret:
        .string "REVTHEWORLD"
main:
        addi    sp,sp,-32
        sd      ra,24(sp)
        sd      s0,16(sp)
        addi    s0,sp,32
        li      a5,11
        sw      a5,-24(s0)
        sw      zero,-20(s0)
        j       .L2
.L5:
        lui     a5,%hi(input)
        addi    a4,a5,%lo(input)
        lw      a5,-20(s0)
        add     a5,a4,a5
        lbu     a5,0(a5)
        addiw   a5,a5,-33
        sb      a5,-25(s0)
        lw      a5,-24(s0)
        addiw   a5,a5,-1
        sext.w  a5,a5
        lw      a4,-20(s0)
        subw    a5,a5,a4
        sext.w  a5,a5
        lui     a4,%hi(secret)
        addi    a4,a4,%lo(secret)
        add     a5,a4,a5
        lbu     a5,0(a5)
        sb      a5,-26(s0)
        lbu     a5,-25(s0)
        mv      a4,a5
        lbu     a5,-26(s0)
        andi    a4,a4,0xff
        andi    a5,a5,0xff
        beq     a4,a5,.L3
        li      a5,-1
        j       .L4
.L3:
        lw      a5,-20(s0)
        addiw   a5,a5,1
        sw      a5,-20(s0)
.L2:
        lw      a5,-20(s0)
        mv      a4,a5
        lw      a5,-24(s0)
        sext.w  a4,a4
        sext.w  a5,a5
        blt     a4,a5,.L5
        li      a5,0
.L4:
        mv      a0,a5
        ld      ra,24(sp)
        ld      s0,16(sp)
        addi    sp,sp,32
        jr      ra