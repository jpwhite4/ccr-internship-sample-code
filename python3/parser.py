#!/usr/bin/env python3
""" slurm log parser """
import sys
import json
import logging

def parse(filename):
    """ Parse out a slurm accounting log file created with the following sacct command

         TZ=UTC sacct --clusters *cluster* --allusers \
            --parsable2 --noheader --allocations --duplicates \
            --format jobid,jobidraw,cluster,partition,account,group,gid,\
        user,uid,submit,eligible,start,end,elapsed,exitcode,state,nnodes,\
        ncpus,reqcpus,reqmem,reqgres,reqtres,timelimit,nodelist,jobname \
            --state CANCELLED,COMPLETED,FAILED,NODE_FAIL,PREEMPTED,TIMEOUT \
            --starttime 2013-01-01T00:00:00 --endtime 2013-01-01T23:59:59 \
            >/tmp/slurm.log

         and convert to a array of dictionaries. There are 25 fields to parse and the delimiter
         is the pipe character '|'.
    """
    fieldnames = 'jobid,jobidraw,cluster,partition,qos,account,group,gid,user,uid,submit,eligible,start,end,elapsed,exitcode,state,nnodes,ncpus,reqcpus,reqmem,reqtres,alloctres,timelimit,nodelist,jobname'.split(',')

    data = []

    with open(filename, "r") as filep:
        for line in filep:
            datum = {}
            for recordid, record in enumerate(line.strip().split("|")):
                datum[fieldnames[recordid]] = record
            data.append(datum)

    return data

def main():
    """ main entry point """
    if len(sys.argv) < 2:
        logging.error("Usage: %s [input filename]", sys.argv[0])
        return

    data = parse(sys.argv[1])
    print(json.dumps(data, indent=4))

if __name__ == "__main__":
    main()
